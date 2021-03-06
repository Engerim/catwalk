const merge = require('webpack-merge')
const PrebuildPlugin = require('prebuild-webpack-plugin')
const paths = require('../paths')
const fs = require('fs')
const os = require('os')
const rimraf = require('rimraf')
const chalk = require('chalk')
const path = require('path')

const links = {
    'common': paths.repositoryRoot + '/paas/libraries/common',
    'catwalk': paths.repositoryRoot + '/paas/catwalk',
    'theme-boost': paths.repositoryRoot + '/paas/themes/frontastic/boost',
}

const fileExists = (path) => {
    try {
        if (fs.existsSync(path)) {
            return true
        }

        // Seems unreachable. Seriously?!?
        return false
    } catch (e) {
        return false
    }
}

const linkPackage = (package, packageDirectory, packageSource) => {

    let packageLocation = path.join(packageDirectory, package)

    if (!fileExists(packageSource)) {
        // This repository does not have the link targets, so we are
        // fine with normal packages.
        console.info(chalk.bold('@frontastic/' + package) + ' installed as common package, since we have no PAAS modifications.')
        return
    }

    if (fileExists(packageLocation) && fs.statSync(packageLocation).isSymbolicLink()) {
        // All fine, this is the expected state
        return
    }

    if (os.platform() === 'win32') {
        console.warn(' ⚠️  Changes in ' + chalk.bold('@frontastic/' + package) + ' won\'t be part of the build. Links required, but not supported on windows.')
        return
    }

    if (fileExists(packageLocation)) {
        // Remove the copied package (from yarn install) to be able to
        // create the links
        rimraf.sync(packageLocation)
    }

    try {
        // Create symlink with relative path to make sure it works inside
        // and outside of the container / virtual machine.
        fs.mkdirSync(packageDirectory, { recursive: true })
        fs.symlinkSync(
            path.relative(packageDirectory, packageSource),
            packageLocation
        )
    } catch (e) {
        console.error(' ⚠️  Wasn\'t able to create symlink for ' + chalk.bold('@frontastic/' + package) + ': ' + e)
        return
    }
}

const ensureLinks = () => {
    for (let package in links) {
        // link packages in the frontend project
        linkPackage(package, path.join(paths.appNodeModules, '@frontastic'), links[package])

        // link packages in the root
        linkPackage(package, path.join(paths.repositoryRoot, 'node_modules', '@frontastic'), links[package])
    }
}

// We run linking already here so, for example tailwind configurations and
// webpack extensions are correctly detected already during the very first run:
ensureLinks()

module.exports = (config, PRODUCTION, SERVER) => {
    return merge(
        config,
        {
            plugins: [
                new PrebuildPlugin({
                    build: ensureLinks,
                    watch: ensureLinks,
                }),
            ],
        },
    )
}
