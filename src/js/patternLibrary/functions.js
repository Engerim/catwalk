import _ from 'lodash'

function ucFirst (string) {
    return string.charAt(0).toUpperCase() + string.slice(1)
}

function displayName (string) {
    return _.map(('' + string).split(/[_ -]/), ucFirst).join('')
}

function isReactComponent (component) {
    return !!(component.component && component.name && component.path)
}

function processPatterns (loader, source = 'Frontastic') {
    let patterns = {}

    loader.keys().forEach(function (fileName) {
        let propertyPath = _.trim(fileName.replace(/(\.?\/|\.[a-z]+$)/g, '.'), '.')

        _.set(
            patterns,
            propertyPath,
            {
                name: displayName(propertyPath.split('.').pop()),
                path: fileName,
                source: source,
                component: loader(fileName).default,
            }
        )
    })

    return patterns
}

export {
    ucFirst,
    displayName,
    isReactComponent,
    processPatterns,
}
