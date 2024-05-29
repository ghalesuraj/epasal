/* eslint-disable */
//wp blocks
const {
    registerBlockType
} = wp.blocks;

//wp block editor
const {
    RichText,
    InspectorControls,
    useBlockProps,
    InnerBlocks
} = wp.blockEditor;

//wp components
const {
    PanelBody,
    TextareaControl,
    Button,
    RangeControl,
    __experimentalNumberControl
} = wp.components;

registerBlockType('springoo/column', {
    title: 'Column',
    description: 'Springoo column widget',
    icon: {
        src: <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15 22.75H9C3.57 22.75 1.25 20.43 1.25 15V9C1.25 3.57 3.57 1.25 9 1.25H15C20.43 1.25 22.75 3.57 22.75 9V15C22.75 20.43 20.43 22.75 15 22.75ZM9 2.75C4.39 2.75 2.75 4.39 2.75 9V15C2.75 19.61 4.39 21.25 9 21.25H15C19.61 21.25 21.25 19.61 21.25 15V9C21.25 4.39 19.61 2.75 15 2.75H9Z" fill="#000000"/>
            <path d="M12 22.75C11.59 22.75 11.25 22.41 11.25 22V2C11.25 1.59 11.59 1.25 12 1.25C12.41 1.25 12.75 1.59 12.75 2V22C12.75 22.41 12.41 22.75 12 22.75Z" fill="#000000"/>
            <path d="M12 10.25H2C1.59 10.25 1.25 9.91 1.25 9.5C1.25 9.09 1.59 8.75 2 8.75H12C12.41 8.75 12.75 9.09 12.75 9.5C12.75 9.91 12.41 10.25 12 10.25Z" fill="#000000"/>
            <path d="M22 15.25H12C11.59 15.25 11.25 14.91 11.25 14.5C11.25 14.09 11.59 13.75 12 13.75H22C22.41 13.75 22.75 14.09 22.75 14.5C22.75 14.91 22.41 15.25 22 15.25Z" fill="#000000"/>
        </svg>
    },
    category: 'springoo',

    // custom attributes
    attributes: {
        columnId: {
            type: 'string'
        },
        smWidth: {
            type: 'number',
            default: 100
        },
        mdWidth: {
            type: 'number',
            default: 50
        },
        lgWidth: {
            type: 'number',
            default: 33
        },
        xlWidth: {
            type: 'number',
            default: 25
        },
        xxlWidth: {
            type: 'number',
            default: 25
        },
        columnGap: {
            type: 'number',
            default: 25
        },
    },

    edit({ clientId, attributes, setAttributes }) {
        const {
            columnId,
            smWidth,
            mdWidth,
            lgWidth,
            xlWidth,
            xxlWidth,
            columnGap,
        } = attributes;

        // custom functions
        setAttributes({ columnId: clientId });

        function onChangeSmWidth( newSmWidth ) {
            setAttributes( { smWidth: newSmWidth } );
        }
        function onChangeMdWidth( newMdWidth ) {
            setAttributes( { mdWidth: newMdWidth } );
        }
        function onChangeLgWidth( newLgWidth ) {
            setAttributes( { lgWidth: newLgWidth } );
        }
        function onChangeXlWidth( newXlWidth ) {
            setAttributes( { xlWidth: newXlWidth } );
        }
        function onChangeXxlWidth( newXxlWidth ) {
            setAttributes( { xxlWidth: newXxlWidth } );
        }
        function onChangeColumnGap( newColumnGap ) {
            setAttributes( { columnGap: newColumnGap } );
        }

        const blockProps = useBlockProps();

        return ([
            <InspectorControls style={ { marginBottom: '40px' } }>
                <PanelBody title={ 'Column Width Settings' }>
                    <RangeControl
                        label="Small Devices width"
                        value={ smWidth }
                        onChange={ onChangeSmWidth }
                        min={ 0 }
                        max={ 100 }
                        step={ 1 }
                    />
                    <RangeControl
                        label="Medium Devices width"
                        value={ mdWidth }
                        onChange={ onChangeMdWidth }
                        min={ 0 }
                        max={ 100 }
                        step={ 1 }
                    />
                    <RangeControl
                        label="Large Devices width"
                        value={ lgWidth }
                        onChange={ onChangeLgWidth }
                        min={ 0 }
                        max={ 100 }
                        step={ 1 }
                    />
                    <RangeControl
                        label="Extra Column width"
                        value={ xlWidth }
                        onChange={ onChangeXlWidth }
                        min={ 0 }
                        max={ 100 }
                        step={ 1 }
                    />
                    <RangeControl
                        label="Extra extra width"
                        value={ xxlWidth }
                        onChange={ onChangeXxlWidth }
                        min={ 0 }
                        max={ 100 }
                        step={ 1 }
                    />
                    <RangeControl
                        label="Column Gap"
                        value={ columnGap }
                        onChange={ onChangeColumnGap }
                        min={ 0 }
                        max={ 1000 }
                        step={ 1 }
                    />
                </PanelBody>
            </InspectorControls>,
            <div { ...blockProps } className={ `springoo-column springoo-column-` + columnId }>
                <p className="springoo-column-text">Springoo Column</p>
                <div className="springoo-column-inner">
                    <InnerBlocks />
                </div>
            </div>
        ]);
    },

    save({ attributes }) {
        const {
            columnId,
            smWidth,
            mdWidth,
            lgWidth,
            xlWidth,
            xxlWidth,
            columnGap,
        } = attributes;

        const blockProps = useBlockProps.save();

        return (
            <>
                <style dangerouslySetInnerHTML={ { __html: `
                    @media (min-width: 576px) {
                        .springoo-column-${ columnId }{
                            width: ${ smWidth }%;
                            flex: 0 0 ${smWidth}%;
                        }
                    }
                    @media (min-width: 768px) {
                        .springoo-column-${ columnId }{
                            width: ${ mdWidth }%;
                            flex: 0 0 ${ mdWidth }%;
                        }
                    }
                    @media (min-width: 992px) {
                        .springoo-column-${ columnId }{
                            width: calc( ${ lgWidth }% - ${columnGap}px );
                            flex: 0 0 calc( ${ lgWidth }% - ${columnGap}px );
                            margin-right: ${columnGap}px;
                        }
                    }
                    @media (min-width: 1200px) {
                        .springoo-column-${ columnId }{
                            width: calc( ${ xlWidth }% - ${columnGap}px );
                            flex: 0 0 calc( ${ xlWidth }% - ${columnGap}px );
                       }
                    }
                    @media (min-width: 1400px) {
                        .springoo-column-${ columnId }{
                            width: calc( ${ xxlWidth }% - ${columnGap}px );
                            flex: 0 0 calc( ${ xxlWidth }% - ${columnGap}px );
                        }
                    }
                `} }>
                </style>
                <div { ...blockProps } className={ `springoo-column springoo-column-` + columnId }>
                    <InnerBlocks.Content />
                </div>
            </>
        );
    }
});
