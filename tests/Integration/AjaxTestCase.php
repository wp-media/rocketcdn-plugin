<?php

namespace RocketCDN\Tests\Integration;

use ReflectionObject;
use RocketCDN\Tests\CallbackTrait;
use RocketCDN\Tests\SettingsTrait;
use WPMedia\PHPUnit\Integration\AjaxTestCase as WPMediaAjaxTestCase;

abstract class AjaxTestCase extends WPMediaAjaxTestCase {
	use CallbackTrait;
	use CapTrait;
	use SettingsTrait;
	use DBTrait;

	protected static $use_settings_trait = false;
	protected static $transients         = [];

	protected $config;

	public static function setUpBeforeClass() : void {
		parent::setUpBeforeClass();

        self::hasAdminCapBeforeClass();

		if ( self::$use_settings_trait ) {
            self::getOriginalSettings();
		}

		if ( ! empty( self::$transients ) ) {
			foreach ( array_keys( self::$transients ) as $transient ) {
				self::$transients[ $transient ] = get_transient( $transient );
			}
		}
	}

	public static function tearDownAfterClass(): void {
		parent::tearDownAfterClass();

		self::resetAdminCap();

		if ( self::$use_settings_trait ) {
            self::resetOriginalSettings();
		}

		foreach ( self::$transients as $transient => $value ) {
			if ( ! empty( $transient ) ) {
				set_transient( $transient, $value );
			} else {
				delete_transient( $transient );
			}
		}
	}

	public function set_up() {
		if ( empty( $this->config ) ) {
			$this->loadTestDataConfig();
		}

		self::removeDBHooks();

		parent::set_up();

		if ( static::$use_settings_trait ) {
			$this->setUpSettings();
		}
	}

	public function tear_down() {
		unset( $_POST['action'], $_POST['nonce'] );
		$this->action = null;
        self::resetAdminCap();

		parent::tear_down();

		if ( static::$use_settings_trait ) {
			$this->tearDownSettings();
		}
	}

	public function configTestData() {
		if ( empty( $this->config ) ) {
			$this->loadTestDataConfig();
		}

		return isset( $this->config['test_data'] )
			? $this->config['test_data']
			: $this->config;
	}

	protected function loadTestDataConfig() {
		$obj      = new ReflectionObject( $this );
		$filename = $obj->getFileName();

		$this->config = $this->getTestData( dirname( $filename ), basename( $filename, '.php' ) );
	}

	protected function setRoleCap( $role_type, $cap ) {
		$role = get_role( $role_type );
		$role->add_cap( $cap );
	}

	protected function removeRoleCap( $role_type, $cap ) {
		$role = get_role( $role_type );
		$role->remove_cap( $cap );
	}
}
