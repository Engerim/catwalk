const merge = require('webpack-merge')
const PrebuildPlugin = require('prebuild-webpack-plugin')
const paths = require('../paths')
const fs = require('fs')
const yaml = require('js-yaml')

const propertiesToHashmap = (object) => {
    let hashmap = {}

    const isObject = (value) => {
        return typeof value === 'object'
    }

    const addDelimiter = (a, b) => {
        return a ? a + b.charAt(0).toUpperCase() + b.slice(1) : b
    }

    const recursePaths = (object = {}, head = '') => {
        Object.keys(object).forEach((key) => {
            let value = object[key]
            let fullPath = addDelimiter(head, value.identifier || key)

            if (isObject(value)) {
                recursePaths(value, fullPath)
            } else if (value) {
                hashmap[fullPath] = value
            }
        })

        return hashmap
    }

    return recursePaths(object)
}

const updateSettings = () => {
    let settings = {}

    try {
        let config = fs.readFileSync(paths.appSrc + '/../config/project.yml')
        let layout = yaml.safeLoad(config).data.layout || {}
        settings = propertiesToHashmap(layout)
    } catch (e) {
        console.error("Could not read theme configuration:" + e)
    }

    fs.writeFileSync(
        paths.appSrc + '/scss/settings.scss',
        Object.entries(settings).reduce((head, [key, value]) => {
            return head + `\$${key}: '${value}';\n`
        }, `// This is an auto generated file, please edit config/project.yml instead\n\n`)
    )

    fs.writeFileSync(
        paths.appSrc + '/js/settings.js',
        Object.entries(settings).reduce((head, [key, value]) => {
            return head + `    ${key}: '${value}',\n`
        }, `// This is an auto generated file, please edit config/project.yml instead\n\nmodule.exports = {\n`) +
        `}\n`
    )
}

// Create file before anything else happens, especially before the tailind
// configuration is imported / processed by webpack
updateSettings()

module.exports = (config, PRODUCTION, SERVER) => {
    return merge(
        config,
        {
            plugins: [
                new PrebuildPlugin({
                    build: updateSettings,
                    watch: updateSettings,
                }),
            ],
        },
    )
}
