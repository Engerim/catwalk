//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component } from 'react'

import ComponentInjector from '../../../app/injector'

import AtomsButton from '../../atoms/buttons/button'
import AtomsIcon from '../../atoms/icons/icon'

class MoleculesCartLink extends Component {
    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        return (<AtomsButton component='a' href='#' className='c-navbar__button c-cart-link' aria-label='Mein Warenkorb'>
            <AtomsIcon icon='bag' iconSize='base' />
            <span className='c-cart-link__badge'>2</span>
        </AtomsButton>)
    }
}

MoleculesCartLink.propTypes = {
}

MoleculesCartLink.defaultProps = {
}

export default ComponentInjector.return('MoleculesCartLink', MoleculesCartLink)
