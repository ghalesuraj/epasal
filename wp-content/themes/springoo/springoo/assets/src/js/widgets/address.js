/* eslint-disable */
//wp blocks
const {
    registerBlockType
} = wp.blocks;

//wp block editor
const {
    RichText,
    InspectorControls,
    MediaUpload,
} = wp.blockEditor;

//wp components
const {
    PanelBody,
    TextareaControl,
    Button,
} = wp.components;

registerBlockType('springoo/address', {
    title: 'Address',
    description: 'Springoo address widget',
    icon: {
        src: <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 14.1699C9.87 14.1699 8.13 12.4399 8.13 10.2999C8.13 8.15994 9.87 6.43994 12 6.43994C14.13 6.43994 15.87 8.16994 15.87 10.3099C15.87 12.4499 14.13 14.1699 12 14.1699ZM12 7.93994C10.7 7.93994 9.63 8.99994 9.63 10.3099C9.63 11.6199 10.69 12.6799 12 12.6799C13.31 12.6799 14.37 11.6199 14.37 10.3099C14.37 8.99994 13.3 7.93994 12 7.93994Z" fill="#000000"/>
            <path d="M12.0001 22.76C10.5201 22.76 9.03005 22.2 7.87005 21.09C4.92005 18.25 1.66005 13.72 2.89005 8.33C4.00005 3.44 8.27005 1.25 12.0001 1.25C12.0001 1.25 12.0001 1.25 12.0101 1.25C15.7401 1.25 20.0101 3.44 21.1201 8.34C22.3401 13.73 19.0801 18.25 16.1301 21.09C14.9701 22.2 13.4801 22.76 12.0001 22.76ZM12.0001 2.75C9.09005 2.75 5.35005 4.3 4.36005 8.66C3.28005 13.37 6.24005 17.43 8.92005 20C10.6501 21.67 13.3601 21.67 15.0901 20C17.7601 17.43 20.7201 13.37 19.6601 8.66C18.6601 4.3 14.9101 2.75 12.0001 2.75Z" fill="#000000"/>
        </svg>
    },
    category: 'springoo',

    // custom attributes
    attributes: {
        title: {
            type: 'string',
            default: '',
        },
        address: {
            type: 'string',
            source: 'html',
            selector: 'p'
        },
    },

    edit({ attributes, setAttributes }) {
        const {
            title,
            address,
        } = attributes;

        // custom functions

        function onChangeTitle( newTitle ) {
            setAttributes( { title: newTitle } );
        }
        function onChangeAddress( newAddress ) {
            setAttributes( { address: newAddress } );
        }

        return ([
            <InspectorControls style={ { marginBottom: '40px' } }>
                <PanelBody title={ 'App Settings' }>
                    <p><strong>Enter Title:</strong></p>
                    <RichText
                        key="phone"
                        tagName="h3"
                        placeholder="Enter Title"
                        value={ title }
                        onChange={ onChangeTitle }
                    />
                    <TextareaControl
                        label="Enter Address:"
                        help="Enter your store Address here"
                        value={ address }
                        onChange={ onChangeAddress }
                    />
                </PanelBody>
            </InspectorControls>,
            <div className="springoo-address-wrap">
                <h3>{ title }</h3>
                <p>{ address }</p>
            </div>
        ]);
    },

    save({ attributes }) {
        const {
            title,
            address,
        } = attributes;

        return (
            <div className="springoo-address-wrap">
                <h2 className="widgettitle">{ title }</h2>
                <p>{ address }</p>
            </div>
        );
    }
});
