/*eslint-env es6*/
( ( document, window ) => {
	'use strict';

    window.addEventListener( 'load', () => {
		let formNoKey = document.querySelector( '#rocketcdn-no-key' ),
            formHasKey = document.querySelector( '#rocketcdn-has-key #rocketcdn_api_key' ),
            apiKey = document.getElementById( 'rocketcdn_api_key' ),
            clearCache = document.getElementById( 'rocketcdn-purge-cache' ),
            postData = '';

		if ( null !== formNoKey ) {
			formNoKey.addEventListener( 'submit', ( e ) => {
				e.preventDefault();

                postData += 'action=rocketcdn_validate_key';
                postData += '&api_key=' + apiKey.value;
			    postData += '&nonce=' + rocketcdn_ajax_data.nonce;

				const request = sendHTTPRequest( postData );
            
                request.onreadystatechange = () => {
                    if ( request.readyState === XMLHttpRequest.DONE && 200 === request.status ) {
                        let responseTxt = JSON.parse(request.responseText);
                        
                        if ( ! responseTxt.success ) {
                            document.getElementById( 'rocketcdn-error-notice' ).innerHTML = responseTxt.data;
                            return false;
                        }

                        formNoKey.submit();
                    }
                };
			} );
		}

        if ( null !== formHasKey ) {
			formHasKey.addEventListener( 'focusout', ( e ) => {
                postData += 'action=rocketcdn_update_key';
                postData += '&api_key=' + apiKey.value;
			    postData += '&nonce=' + rocketcdn_ajax_data.nonce;

				const request = sendHTTPRequest( postData );
            
                request.onreadystatechange = () => {
                    if ( request.readyState === XMLHttpRequest.DONE && 200 === request.status ) {
                        let responseTxt = JSON.parse(request.responseText);
                        
                        if ( ! responseTxt.success ) {
                            document.getElementById( 'rocketcdn-error-notice' ).innerHTML = responseTxt.data;
                            return;
                        }

                        document.getElementById( 'rocketcdn-error-notice' ).innerHTML = responseTxt.data;
                    }
                };
			} );
		}

        if ( null !== clearCache ) {
			clearCache.addEventListener( 'click', ( e ) => {
                e.preventDefault();

                postData += 'action=rocketcdn_purge_cache';
			    postData += '&nonce=' + rocketcdn_ajax_data.nonce;

				const request = sendHTTPRequest( postData );
            
                request.onreadystatechange = () => {
                    if ( request.readyState === XMLHttpRequest.DONE && 200 === request.status ) {
                        let responseTxt = JSON.parse(request.responseText);
                        
                        if ( ! responseTxt.success ) {
                            document.getElementById( 'rocketcdn-purge-cache-result' ).innerHTML = responseTxt.data;
                            return;
                        }

                        document.getElementById( 'rocketcdn-purge-cache-result' ).innerHTML = responseTxt.data;
                    }
                };
			} );
		}
	} );

    function sendHTTPRequest( postData ) {
        const httpRequest = new XMLHttpRequest();

        httpRequest.open( 'POST', ajaxurl );
        httpRequest.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );
        httpRequest.send( postData );

        return httpRequest;
    }
} )( document, window );
