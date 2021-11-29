<?php
declare(strict_types=1);

namespace RocketCDN\Options;

/**
 * Abstract class implementing OptionsInterface mandatory methods.
 */
abstract class AbstractOptions implements OptionsInterface {
	/**
	 * The prefix used by options.
	 *
	 * @var string
	 */
	protected $prefix;

	/**
	 * Gets the option name used to store the option in the WordPress database.
	 *
	 * @param string $name Unprefixed name of the option.
	 *
	 * @return string Option name used to store it
	 */
	public function get_option_name( string $name ): string {
		return $this->prefix . $name;
	}

	/**
	 * Gets the option for the given name. Returns the default value if the value does not exist.
	 *
	 * @param string $name   Name of the option to get.
	 * @param mixed  $default Default value to return if the value does not exist.
	 *
	 * @return mixed
	 */
	abstract public function get( string $name, $default = null );

	/**
	 * Sets the value of an option. Update the value if the option for the given name already exists.
	 *
	 * @param string $name Name of the option to set.
	 * @param mixed  $value Value to set for the option.
	 *
	 * @return void
	 */
	abstract public function set( string $name, $value );

	/**
	 * Deletes the option with the given name.
	 *
	 * @param string $name Name of the option to delete.
	 *
	 * @return void
	 */
	abstract public function delete( string $name );

	/**
	 * Checks if the option with the given name exists.
	 *
	 * @param string $name Name of the option to check.
	 *
	 * @return bool
	 */
	public function has( string $name ): bool {
		return null !== $this->get( $name );
	}
}
