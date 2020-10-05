//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component, Fragment } from 'react'

import ComponentInjector from '../../../app/injector'

import fixture from '../../fixture'

import MoleculesHero from './hero'

class MoleculesHeroes extends Component {
    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        return (<Fragment>
            <MoleculesHero
                media={{
                    media: fixture.image.person,
                    title: fixture.headline.short,
                    ratio: '3:1',
                }}
                caption='Large Hero'
                size='large'
            />
            <MoleculesHero
                media={{
                    media: fixture.image.person,
                    title: fixture.headline.short,
                    ratio: '5:1',
                }}
                caption='Tiny Top Left Hero'
                verticalAlign='top'
                horizontalAlign='left'
                size='small'
            />
            <MoleculesHero
                media={{
                    media: fixture.image.person,
                    title: fixture.headline.short,
                }}
                caption='Hero with native aspect ratio'
            />
        </Fragment>)
    }
}

MoleculesHeroes.propTypes = {}

MoleculesHeroes.defaultProps = {}

export default ComponentInjector.return('MoleculesHeroes', MoleculesHeroes)
