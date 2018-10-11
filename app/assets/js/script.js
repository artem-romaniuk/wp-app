
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
