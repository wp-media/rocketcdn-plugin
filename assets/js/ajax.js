/*eslint-env es6*/
( ( document, window ) => {
	'use strict';

    window.addEventListener( 'load', () => {
		let formNoKey = document.querySelector( '#rocketcdn-no-key' ),
            formHasKey = document.querySelector( '#rocketcdn-has-key #rocketcdn_api_key' ),
            apiKey = document.getElementById( 'rocketcdn_api_key' ),
            clearCache = document.getElementById( 'rocketcdn-purge-cache' );

		if ( null !== formNoKey ) {
			formNoKey.addEventListener( 'submit', ( e ) => {
				e.preventDefault();

                let postData = '';
                let errorNotice = document.getElementById( 'rocketcdn-error-notice' );

                postData += 'action=rocketcdn_validate_key';
                postData += '&api_key=' + apiKey.value;
			    postData += '&nonce=' + rocketcdn_ajax_data.nonce;

				const request = sendHTTPRequest( postData );
            
                request.onreadystatechange = () => {
                    if ( request.readyState === XMLHttpRequest.DONE && 200 === request.status ) {
                        let responseTxt = JSON.parse(request.responseText);
                        
                        if ( ! responseTxt.success ) {
                            errorNotice.querySelector( 'p' ).innerHTML = responseTxt.data;
                            errorNotice.classList.add( 'rocketcdn-notice-show','rocketcdn-notice-error' );
                            return false;
                        }

                        formNoKey.submit();
                    }
                };
			} );
		}

        if ( null !== formHasKey ) {
			formHasKey.addEventListener( 'focusout', ( e ) => {
                let postData = '';
                let errorNotice = document.getElementById( 'rocketcdn-error-notice' );

                postData += 'action=rocketcdn_update_key';
                postData += '&api_key=' + apiKey.value;
			    postData += '&nonce=' + rocketcdn_ajax_data.nonce;

				const request = sendHTTPRequest( postData );
            
                request.onreadystatechange = () => {
                    if ( request.readyState === XMLHttpRequest.DONE && 200 === request.status ) {
                        let responseTxt = JSON.parse(request.responseText);
                        
                        errorNotice.querySelector( 'p' ).innerHTML = responseTxt.data;

                        if ( ! responseTxt.success ) {
                            errorNotice.classList.add( 'rocketcdn-notice-show','rocketcdn-notice-error' );
                            errorNotice.classList.remove( 'rocketcdn-notice-success' );
                            return;
                        }

                        errorNotice.classList.add( 'rocketcdn-notice-show','rocketcdn-notice-success' );
                        errorNotice.classList.remove( 'rocketcdn-notice-error' );
                    }
                };
			} );
		}

        if ( null !== clearCache ) {
			clearCache.addEventListener( 'click', ( e ) => {
                e.preventDefault();

                let postData = '';
                let purgeResult = document.getElementById( 'rocketcdn-purge-cache-result' );

                postData += 'action=rocketcdn_purge_cache';
			    postData += '&nonce=' + rocketcdn_ajax_data.nonce;

				const request = sendHTTPRequest( postData );
            
                request.onreadystatechange = () => {
                    if ( request.readyState === XMLHttpRequest.DONE && 200 === request.status ) {
                        let responseTxt = JSON.parse(request.responseText);

                        purgeResult.innerHTML = responseTxt.data;

                        if ( ! responseTxt.success ) {
                            purgeResult.classList.add( 'rocketcdn-purge-cache-error', 'rocketcdn-notice-show' );
                            purgeResult.classList.remove( 'rocketcdn-purge-cache-success' );
                            return;
                        }

                        purgeResult.classList.add( 'rocketcdn-purge-cache-success', 'rocketcdn-notice-show' );
                        purgeResult.classList.remove( 'rocketcdn-purge-cache-error' );
                    }
                };
			} );
		}

        document.querySelector( '.rocketcdn-notice-dismiss' ).addEventListener( 'click', ( e ) => {
            e.preventDefault();

            document.getElementById( 'rocketcdn-error-notice' ).classList.remove( 'rocketcdn-notice-show', 'rocketcdn-notice-error', 'rocketcdn-notice-success' );
        } );
	} );

    function sendHTTPRequest( postData ) {
        const httpRequest = new XMLHttpRequest();

        httpRequest.open( 'POST', ajaxurl );
        httpRequest.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
        httpRequest.send( postData );

        return httpRequest;
    }
} )( document, window );
