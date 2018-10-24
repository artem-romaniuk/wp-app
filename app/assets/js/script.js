
String.prototype.replaceAll = function(search, replace)
{
    return this.split(search).join(replace);
}


function createItem(containerObject, scopeItemObject, itemsObject, templateString)
{
    const cloneItem = scopeItemObject
        .clone()[0]
        .outerHTML
        .replaceAll(templateString, getItemId(itemsObject.children()));

    itemsObject.append(cloneItem);
}

function deleteItem(buttonObject)
{
    buttonObject
        .parent()
        .remove();
}

function getItemId(items)
{
    if (items.length > 0)
    {
        const arrayItems = [];

        for (let i = 0; i < items.length; i++)
        {
            let propertyValue = +jQuery(items[i]).attr('data-item-id');

            arrayItems.push(propertyValue);
        }

        return Math.max.apply(null, arrayItems) + 1;
    }

    return 1;
}

function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));

    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function setCookie(name, value, options)
{
    options = options || {};

    let expires = options.expires;

    if (typeof expires == "number" && expires)
    {
        let d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }

    if (expires && expires.toUTCString)
    {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    let updatedCookie = name + "=" + value;

    for (let propName in options)
    {
        updatedCookie += "; " + propName;
        let propValue = options[propName];
        if (propValue !== true)
        {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}

function deleteCookie(name)
{
    setCookie(name, '', {
        expires: -1
    })
}


/* Building MetaBox Constructor handler */
jQuery(document).ready(function($)
{
    $(function()
    {
        const componentsContainer = $('#componentsContainer');
        const componentPlaceholder = $('input[name=component_placeholder]').val();

        if ($('*').is(componentsContainer))
        {
            /* Create component */
            $('.clone-component').on('click', function (e)
            {
                const componentName = $(this).attr('data');

                if ($('*').is('.' + componentName))
                {
                    const cloneComponent = $('.' + componentName)
                        .clone()[0]
                        .outerHTML
                        .replaceAll(componentPlaceholder, computationComponentId());
                    componentsContainer.append(cloneComponent);

                    const sortableArray = componentsContainer
                        .sortable('refreshPositions')
                        .sortable('toArray');
                    refreshOrder(sortableArray);

                    const urlWithoutHash = document.location.href.replace(location.hash , '');
                    location.replace(urlWithoutHash + '#' + $(cloneComponent).attr('id'));
                }
            });
            /* End Create component */

            /* Sorted components */
            componentsContainer.sortable({
                axis: 'y',
                tolerance: 'pointer',
                cursor: 'move',
                update: function(event, ui)
                {
                    const sortableArray = $(this).sortable('toArray');
                    refreshOrder(sortableArray);
                }
            });
            /* End Sorted components */

            /* Delete component */
            $(document).on('click', '.delete-component-button', function(e)
            {
                e.preventDefault();

                const that = this;
                const confirmDeletePopUp = $(that)
                    .parents('.component-container')
                    .find('.confirm-delete-component');
                confirmDeletePopUp.show();
                confirmDeletePopUp
                    .find('.confirm-action-button')
                    .on('click', function()
                    {
                        if ($(this).attr('data-confirm') === 'confirm')
                        {
                            $(this).parent().parent().remove();
                            confirmDeletePopUp.hide();
                        }
                        else
                        {
                            confirmDeletePopUp.hide();
                        }
                    });

                $(document).on('click', function (e)
                {
                    if (!confirmDeletePopUp.is(e.target) && !$(that).is(e.target) && confirmDeletePopUp.has(e.target).length === 0)
                    {
                        confirmDeletePopUp.hide();
                    }
                });
            });
            /* End Delete component */

            /* Show / hide component */
            $('.show-hide-checkbox').each(function ()
            {
                onOffBlock(this);
            });

            $(document).on('change', '.show-hide-checkbox', function()
            {
                onOffBlock(this);
            });
            /* End Show / hide component */
        }

        function refreshOrder(elements)
        {
            if (elements.length > 0)
            {
                elements.forEach(function (item, i)
                {
                    $('#' + elements[i])
                        .find('.position-component')
                        .val(i + 1);
                });
            }
        }

        function onOffBlock(element)
        {
            const blockBodyFields = $(element)
                .parents('.component-container')
                .find('.display-layout');

            if ($(element).is(':checked'))
            {
                blockBodyFields.removeClass('display-off');
            }
            else
            {
                blockBodyFields.addClass('display-off');
            }
        }

        function computationComponentId()
        {
            const components = componentsContainer.children();

            if (components.length > 0)
            {
                const arrayComponents = [];

                for (let i = 0; i < components.length; i++)
                {
                    let propertyValue = +$(components[i]).attr('data-component-id');
                    arrayComponents.push(propertyValue);
                }

                return Math.max.apply(null, arrayComponents) + 1;
            }

            return 1;
        }

    });



    /* Slide show/hide components */
    const components = $('body').find('.component-container');

    if (components.length > 0)
    {
        components.each(function ()
        {
            const component = $(this).attr('id');
            const componentStatus = getCookie(component);
            const indicator  = $(this).find('.toggle-indicator');
            const body = $(this).find('.body-block');
            const footer = $(this).find('.footer-block');

            if (componentStatus)
            {
                if (componentStatus == 'hidden')
                {
                    indicator.addClass('slide-up-true');
                    body.slideUp(0);
                    footer.slideUp(0);
                }
                else
                {
                    indicator.removeClass('slide-up-true');
                    body.slideDown(0);
                    footer.slideDown(0);
                }
            }
        });
    }

    $('.slide-up').on('click', function()
    {
        const component = $(this).parents('.component-container');
        const componentId = component.attr('id');
        const indicator  = $(this).find('.toggle-indicator');
        const body = component.find('.body-block');
        const footer = component.find('.footer-block');

        if (indicator.hasClass('slide-up-true'))
        {
            setCookie(componentId, 'showed', {expires:3600, path:'/'});
            indicator.removeClass('slide-up-true');
            body.slideDown(0);
            footer.slideDown(0);
        }
        else
        {
            setCookie(componentId, 'hidden', {expires:3600, path:'/'});
            indicator.addClass('slide-up-true');
            body.slideUp(0);
            footer.slideUp(0);
        }
    });
    /* End Slide show/hide components */

});
/* End Building MetaBox Constructor handler */


/* For choise and remove images in MetaBox */
function choiceImage(e, obj) {
    e.preventDefault();

    let frame;
    let imageWrapper = $(obj).parents('.image-wrapper');

    if (frame) {
        frame.open();
        return;
    }

    frame = wp.media.frames.questImgAdd = wp.media({
        states: [
            new wp.media.controller.Library({
                //title: 'Добавить изображекние',
                library: wp.media.query({type: 'image'}),
                multiple: false
                //date: false
            })
        ],
        button: {
            //text: '',
        }
    });

    frame.on('select', function () {
        const selected = frame
            .state()
            .get('selection')
            .first()
            .toJSON();
        if (selected) {
            imageWrapper.find('.image-id').val(selected.id);
            imageWrapper.find('img').attr('src', selected.sizes.thumbnail.url);
        }
    });

    frame.on('open', function () {
        const imageID = imageWrapper.find('.image-id').val();
        if (imageID) {
            frame
                .state()
                .get('selection')
                .add(wp.media.attachment(imageID));
        }
    });
    frame.open();
}

function removeImage(obj) {

    $(obj).parent().find('.image-id').val('0');
    $(obj).parent().find('img').attr('src', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkAQMAAABKLAcXAAAABlBMVEUAAAC7u7s37rVJAAAAAXRSTlMAQObYZgAAACJJREFUOMtjGAV0BvL/G0YMr/4/CDwY0rzBFJ704o0CWgMAvyaRh+c6m54AAAAASUVORK5CYII=');
}
/* End For choise and remove images in MetaBox */