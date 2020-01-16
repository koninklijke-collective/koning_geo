/**
 * Module: TYPO3/CMS/KoningGeo/FindAddress
 *
 * JavaScript to handle google address geocode API implementation
 * @exports TYPO3/CMS/KoningGeo/FindAddress
 */
define(['jquery', 'TYPO3/CMS/Backend/FormEngine', 'TYPO3/CMS/Core/Contrib/jquery.autocomplete'], function ($, FormEngine) {
    'use strict';

    /**
     * @exports TYPO3/CMS/KoningGeo/FindAddress
     */
    var KoningGeoFindAddress = {};

    KoningGeoFindAddress.initialize = function ($searchField) {
        var $containerElement = $searchField.closest('.koning-geo.find-address-container');
        var table = $searchField.data('table'),
            field = $searchField.data('field'),
            uid = $searchField.data('uid'),
            newRecordRow = $searchField.data('recorddata'),
            minimumCharacters = $searchField.data('minchars') || 2,
            delay = $searchField.data('delay') || 500,
            params = {
                'table': table,
                'field': field,
                'uid': uid,
                'newRecordRow': newRecordRow
            },
            insertValue = function (element) {
                var response = $(element).data('response'),
                    resultContainer = $containerElement.find('.address-result'),
                    formEl = $searchField.data('field'),
                    labelEl = $('<div>').html($(element).data('label')),
                    label = labelEl.text(),
                    title = labelEl.find('span').attr('title') || label;

                if (!response) {
                    return;
                }

                resultContainer.show()
                    .removeClass('callout-success')
                    .addClass('callout-info');

                if (response.formatted_address) {
                    resultContainer.find('.address-label').html(response.formatted_address);
                }
                if (response.geometry.location) {
                    resultContainer.find('.address-latitude').html(response.geometry.location.lat);
                    resultContainer.find('.address-longitude').html(response.geometry.location.lng);
                }

                FormEngine.setSelectOptionFromExternalSource(formEl, JSON.stringify(response), label, title);
                TBE_EDITOR.fieldChanged(table, uid, field, formEl);
            };

        $searchField.autocomplete({
            // ajax options
            serviceUrl: TYPO3.settings.ajaxUrls['koning-geo-find-address'],
            deferRequestBy: delay,
            params: params,
            type: 'POST',
            paramName: 'value',
            dataType: 'json',
            minChars: minimumCharacters,
            groupBy: 'typeLabel',
            containerClass: 'autocomplete-results',
            appendTo: $containerElement.find('.autocomplete'),
            forceFixPosition: false,
            preserveInput: true,
            showNoSuggestionNotice: true,
            noSuggestionNotice: '<div class="autocomplete-info">No results</div>',
            minLength: minimumCharacters,
            // put the AJAX results in the right format
            transformResult: function (response) {
                return {
                    suggestions: $.map(response, function (dataItem) {
                        return {value: dataItem.text, data: dataItem};
                    })
                };
            },
            // Rendering of each item
            formatResult: function (suggestion, value) {
                return $('<div>').append(
                    $('<a class="autocomplete-suggestion-link" href="#">' +
                        suggestion.data.sprite + suggestion.data.text +
                        '</a>').attr({
                        'data-label': suggestion.data.label,
                        'data-response': JSON.stringify(suggestion.data.response)
                    })
                ).html();
            },
            onSearchComplete: function () {
                $containerElement.addClass('open');
            },
            beforeRender: function (container) {
                // Unset height, width and z-index again, should be fixed by the plugin at a later point
                container.attr('style', '');
                $containerElement.addClass('open');
            },
            onHide: function () {
                $containerElement.removeClass('open');
            },
            onSelect: function () {
                insertValue($containerElement.find('.autocomplete-selected a')[0]);
            }
        });

        // set up the events
        $containerElement.on('click', '.autocomplete-suggestion-link', function (evt) {
            evt.preventDefault();
            insertValue(this);
        });
    };

    /**
     * Initializes events using deferred bound to document so AJAX reloads are no problem
     */
    KoningGeoFindAddress.initializeEvents = function () {
        $('.koning-geo-form-find-location').each(function (key, el) {
            if (!$(el).data('t3-koning-geo-find-address-initialized')) {
                KoningGeoFindAddress.initialize($(el));
                $(el).data('t3-koning-geo-find-address-initialized', true);
            }
        });
    };
    $(KoningGeoFindAddress.initializeEvents);

    return KoningGeoFindAddress;
});
