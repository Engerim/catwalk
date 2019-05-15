import React, { Component } from 'react'
import './_container.scss'

export default class Container extends Component {
    render() {
        const { story } = this.props

        return (
            <div role='main' className='main'>
                {story()}
            </div>
        )
    }
}