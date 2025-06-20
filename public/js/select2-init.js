/**
 * Select2 Initialization Script for Arabic RTL Support
 * التكافل الصحي - نظام إدارة المراكز الطبية
 */

$(document).ready(function() {
    // Default Select2 configuration
    const defaultConfig = {
        theme: 'bootstrap-5',
        language: 'ar',
        dir: 'rtl',
        allowClear: true,
        width: '100%',
        placeholder: function() {
            return $(this).data('placeholder') || 'اختر...';
        }
    };

    // Initialize Select2 for all select elements
    function initializeSelect2() {
        $('select:not(.select2-hidden-accessible):not(.no-select2)').each(function() {
            const $select = $(this);
            
            // Get custom configuration
            const config = $.extend({}, defaultConfig, {
                placeholder: $select.find('option[value=""]').text() || 
                           $select.data('placeholder') || 
                           'اختر...',
                dropdownParent: getDropdownParent($select)
            });

            // Add search for selects with many options
            if ($select.find('option').length > 10) {
                config.minimumResultsForSearch = 0;
            } else {
                config.minimumResultsForSearch = Infinity;
            }

            // Special handling for specific selects
            if ($select.hasClass('searchable')) {
                config.minimumResultsForSearch = 0;
            }

            if ($select.hasClass('no-clear')) {
                config.allowClear = false;
            }

            // Initialize Select2
            try {
                $select.select2(config);
                
                // Add custom styling
                addCustomStyling($select);
                
            } catch (error) {
                console.warn('Failed to initialize Select2 for element:', $select, error);
            }
        });
    }

    // Get appropriate dropdown parent
    function getDropdownParent($select) {
        const $modal = $select.closest('.modal');
        const $offcanvas = $select.closest('.offcanvas');
        
        if ($modal.length) {
            return $modal;
        } else if ($offcanvas.length) {
            return $offcanvas;
        }
        
        return $('body');
    }

    // Add custom styling to Select2 elements
    function addCustomStyling($select) {
        const $container = $select.next('.select2-container');
        
        // Add Bootstrap classes
        $container.find('.select2-selection').addClass('form-control');
        
        // Handle validation states
        if ($select.hasClass('is-invalid')) {
            $container.find('.select2-selection').addClass('is-invalid');
        }
        
        if ($select.hasClass('is-valid')) {
            $container.find('.select2-selection').addClass('is-valid');
        }
    }

    // Handle dynamic content
    function handleDynamicContent() {
        // Re-initialize Select2 when new content is added
        const observer = new MutationObserver(function(mutations) {
            let shouldReinitialize = false;
            
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            const $node = $(node);
                            if ($node.is('select') || $node.find('select').length) {
                                shouldReinitialize = true;
                            }
                        }
                    });
                }
            });
            
            if (shouldReinitialize) {
                setTimeout(initializeSelect2, 100);
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    // Handle form validation
    function handleFormValidation() {
        $(document).on('invalid.bs.form', 'select.select2-hidden-accessible', function() {
            const $select = $(this);
            const $container = $select.next('.select2-container');
            $container.find('.select2-selection').addClass('is-invalid');
        });

        $(document).on('change', 'select.select2-hidden-accessible', function() {
            const $select = $(this);
            const $container = $select.next('.select2-container');
            $container.find('.select2-selection').removeClass('is-invalid');
            
            if ($select.val()) {
                $container.find('.select2-selection').addClass('is-valid');
            }
        });
    }

    // Custom Select2 methods
    window.Select2Helper = {
        // Reinitialize specific select
        reinitialize: function(selector) {
            $(selector).select2('destroy');
            setTimeout(function() {
                initializeSelect2();
            }, 50);
        },
        
        // Add option to select
        addOption: function(selector, value, text, selected = false) {
            const $select = $(selector);
            const $option = $('<option></option>').attr('value', value).text(text);
            
            if (selected) {
                $option.prop('selected', true);
            }
            
            $select.append($option);
            $select.trigger('change');
        },
        
        // Clear and reload options
        reloadOptions: function(selector, options) {
            const $select = $(selector);
            $select.empty();
            
            if ($select.data('placeholder')) {
                $select.append('<option value="">' + $select.data('placeholder') + '</option>');
            }
            
            $.each(options, function(value, text) {
                $select.append('<option value="' + value + '">' + text + '</option>');
            });
            
            $select.trigger('change');
        },
        
        // Set value
        setValue: function(selector, value) {
            $(selector).val(value).trigger('change');
        },
        
        // Get selected text
        getSelectedText: function(selector) {
            return $(selector).find('option:selected').text();
        }
    };

    // Initialize everything
    initializeSelect2();
    handleDynamicContent();
    handleFormValidation();

    // Handle Bootstrap modal events
    $(document).on('shown.bs.modal', '.modal', function() {
        $(this).find('select:not(.select2-hidden-accessible):not(.no-select2)').each(function() {
            if (!$(this).hasClass('select2-hidden-accessible')) {
                initializeSelect2();
            }
        });
    });

    // Handle Bootstrap offcanvas events
    $(document).on('shown.bs.offcanvas', '.offcanvas', function() {
        $(this).find('select:not(.select2-hidden-accessible):not(.no-select2)').each(function() {
            if (!$(this).hasClass('select2-hidden-accessible')) {
                initializeSelect2();
            }
        });
    });

    // Debug mode
    if (window.location.search.includes('debug=select2')) {
        console.log('Select2 Debug Mode Enabled');
        window.Select2Debug = {
            listAll: function() {
                console.log('All Select2 instances:', $('.select2-hidden-accessible'));
            },
            checkConfig: function(selector) {
                const $select = $(selector);
                console.log('Select2 config for', selector, ':', $select.data('select2'));
            }
        };
    }
});

// CSS fixes for better RTL support
const rtlStyles = `
<style>
.select2-container--bootstrap-5[dir="rtl"] .select2-selection--single {
    text-align: right;
}

.select2-container--bootstrap-5[dir="rtl"] .select2-selection--single .select2-selection__rendered {
    padding-right: 12px;
    padding-left: 20px;
}

.select2-container--bootstrap-5[dir="rtl"] .select2-selection--single .select2-selection__arrow {
    left: 1px;
    right: auto;
}

.select2-container--bootstrap-5[dir="rtl"] .select2-search--dropdown .select2-search__field {
    text-align: right;
}

.select2-container--bootstrap-5[dir="rtl"] .select2-results__option {
    text-align: right;
}

.select2-container--bootstrap-5 .select2-dropdown {
    z-index: 9999;
}

.modal .select2-container--bootstrap-5 .select2-dropdown {
    z-index: 10000;
}

.offcanvas .select2-container--bootstrap-5 .select2-dropdown {
    z-index: 10000;
}
</style>
`;

// Inject RTL styles
if (!document.getElementById('select2-rtl-styles')) {
    const styleElement = document.createElement('div');
    styleElement.id = 'select2-rtl-styles';
    styleElement.innerHTML = rtlStyles;
    document.head.appendChild(styleElement);
}
