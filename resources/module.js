// set global vars
var iframeModal;
var iframeMessage;

humhub.module('iframe', function (module, require, $) {
    module.initOnPjaxLoad = true;

    // Wait for elements to be loaded
    var init = function(isPjax) { 
        
        // When IframeResize plugin is loaded (in resources folder)
        iFrameResize(
            {
                log: false,
                scrolling: false, // if iframed website has not the content window javascript
                inPageLinks: true,

                // Each time iframed website has loaded the content window javascript
                onInit: function(messageData) {
                    // Remove scrollbar
                    $('#iframe-page').attr('scrolling', 'no');
                },

                // Each time iframed page is loaded or URL changes
                onMessage: function(messageData) {
                    // message sent by iframed website is : {
                    //   url: window.location.href,
                    //   title: document.getElementsByTagName("title")[0].innerText
                    // }
                    iframeMessage = messageData.message; // update global var

                    // Load comments with ajax, after the iframe tag, each time URL changes in the iframed website
                    $.pjax.reload('#iframe-comments', {
                        type : 'POST',
                        url: urlContentActionUrl,
                        data: {
                            containerPageId: $('#iframe-page').attr('data-container-page-id'),
                            iframeMessage: iframeMessage,
                        }
                    });
                },
            },
            '#iframe-page iframe'
        );

        iframeModal = require('ui.modal');
    }

    module.export({
        init: init
    });
});

// Hide sidebar if needed
$(document).on('humhub:ready', function() {
    if (hideSidebar) {
        $('#wrapper').addClass('toggled');
        hideSidebar = false; // can be reactivated by `url-content.php`
    }
});