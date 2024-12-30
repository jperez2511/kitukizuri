function typeArray(data) {
    // Verificar si la entrada es un array
    if (Array.isArray(data)) {
        const firstElement = data[0];

        // Estructura 1: Array de objetos con claves 'input' y 'value'
        if (
            typeof firstElement === 'object' &&
            firstElement !== null &&
            data.every(item => item && 'input' in item && 'value' in item)
        ) {
            return 1;
        }

        // Estructura 2: Array de arrays secuenciales con claves [0, 1]
        if (
            Array.isArray(firstElement) &&
            data.every(item => Array.isArray(item) && item.length === 2)
        ) {
            return 2;
        }
    }

    // Estructura 3: Objeto simple con claves '0' y '1'
    if (
        typeof data === 'object' &&
        data !== null &&
        Object.keys(data).join(',') === '0,1'
    ) {
        return 3;
    }

    // Estructura 4: Objeto simple con claves 'input' y 'value'
    if (
        typeof data === 'object' &&
        data !== null &&
        Object.keys(data).join(',') === 'input,value'
    ) {
        return 4;
    }

    // No coincide con ninguna estructura
    return false;
}


export function hideElements(dependencies, dynamicElement) {
    console.log(dependencies);
    const type = typeArray(dependencies);

    if (!type) {
        console.error('La estructura del array no es válida.');
        return;
    }

    let element, value;
    switch (type) {
        case 1:
            dependencies.forEach(dependency => {
                element = $(`#${dependency.input}`);
                const value = dependency.value;

                if (element.value == value) {
                    dynamicElement.show();
                } else {
                    dynamicElement.hide();
                }
                
            });
            break;

        case 2:
            dependencies.forEach(dependency => {
                element = $(`#${dependency[0]}`);
                const value = dependency[1];

                if (element.length) {
                    if (element.is('input[type="checkbox"]')) {
                        element.prop('checked', value);
                    } else {
                        element.val(value);
                    }

                    element.trigger('change');
                }
            });
            break;

        case 3:
            element = $(`#${dependencies[0]}`);
            value = dependencies[1];

            if (element.length) {
                if (element.is('input[type="checkbox"]')) {
                    element.prop('checked', value);
                } else {
                    element.val(value);
                }

                element.trigger('change');
            }
            break;

        case 4:
            element = $(`#${dependencies.input}`);
            value = dependencies.value;

            if (element.length) {
                if (element.is('input[type="checkbox"]')) {
                    element.prop('checked', value);
                } else {
                    element.val(value);
                }

                element.trigger('change');
            }
            break;
    }
}