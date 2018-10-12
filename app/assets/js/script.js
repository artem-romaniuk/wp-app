
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
});
/* End Building MetaBox Constructor handler */
