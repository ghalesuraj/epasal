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

registerBlockType('springoo/app', {
    title: 'Download App',
    description: 'Springoo App info widget',
    icon: {
        src: <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 17.75C8.9 17.75 8.81 17.73 8.71 17.69C8.43 17.58 8.25 17.3 8.25 17V11C8.25 10.59 8.59 10.25 9 10.25C9.41 10.25 9.75 10.59 9.75 11V15.19L10.47 14.47C10.76 14.18 11.24 14.18 11.53 14.47C11.82 14.76 11.82 15.24 11.53 15.53L9.53 17.53C9.39 17.67 9.19 17.75 9 17.75Z" fill="#000000"/>
            <path d="M9 17.7499C8.81 17.7499 8.62 17.6799 8.47 17.5299L6.47 15.5299C6.18 15.2399 6.18 14.7599 6.47 14.4699C6.76 14.1799 7.24 14.1799 7.53 14.4699L9.53 16.4699C9.82 16.7599 9.82 17.2399 9.53 17.5299C9.38 17.6799 9.19 17.7499 9 17.7499Z" fill="#000000"/>
            <path d="M15 22.75H9C3.57 22.75 1.25 20.43 1.25 15V9C1.25 3.57 3.57 1.25 9 1.25H14C14.41 1.25 14.75 1.59 14.75 2C14.75 2.41 14.41 2.75 14 2.75H9C4.39 2.75 2.75 4.39 2.75 9V15C2.75 19.61 4.39 21.25 9 21.25H15C19.61 21.25 21.25 19.61 21.25 15V10C21.25 9.59 21.59 9.25 22 9.25C22.41 9.25 22.75 9.59 22.75 10V15C22.75 20.43 20.43 22.75 15 22.75Z" fill="#000000"/>
            <path d="M22 10.75H18C14.58 10.75 13.25 9.41999 13.25 5.99999V1.99999C13.25 1.69999 13.43 1.41999 13.71 1.30999C13.99 1.18999 14.31 1.25999 14.53 1.46999L22.53 9.46999C22.74 9.67999 22.81 10.01 22.69 10.29C22.57 10.57 22.3 10.75 22 10.75ZM14.75 3.80999V5.99999C14.75 8.57999 15.42 9.24999 18 9.24999H20.19L14.75 3.80999Z" fill="#000000"/>
        </svg>

    },
    category: 'springoo',

    // custom attributes
    attributes: {
        title: {
            type: 'string',
            default: '',
        },
        desc: {
            type: 'string',
            source: 'html',
            selector: 'p'
        },
        googlePlayImg: {
            type: 'string',
            source: 'attribute',
            selector: '.google-play-img',
            attribute: 'src',
        },
        googlePlayUrl: {
            source: 'attribute',
            selector: '.google-play-url',
            attribute: 'href'
        },
        appStoreImg: {
            type: 'string',
            source: 'attribute',
            selector: '.app-store-img',
            attribute: 'src',
        },
        appStoreUrl: {
            source: 'attribute',
            selector: '.app-store-url',
            attribute: 'href'
        },
    },

    edit({ attributes, setAttributes }) {
        const {
            title,
            desc,
            googlePlayImg,
            googlePlayUrl,
            appStoreImg,
            appStoreUrl,
        } = attributes;

        // custom functions

        function onChangeTitle( newTitle ) {
            setAttributes( { title: newTitle } );
        }
        function onChangeDesc( newDesc ) {
            setAttributes( { desc: newDesc } );
        }

        function onSelectAppStoreImg(newAppStoreImg){
            setAttributes({ appStoreImg: newAppStoreImg.sizes.full.url });
        }

        function onChangeAppStoreUrl( newAppStoreUrl ) {
            setAttributes( { appStoreUrl: newAppStoreUrl } );
        }

        function onSelectGooglePlayImg(newGooglePlayImg){
            setAttributes({ googlePlayImg: newGooglePlayImg.sizes.full.url });
        }

        function onChangeGooglePlayUrl( newGooglePlayUrl ) {
            setAttributes( { googlePlayUrl: newGooglePlayUrl } );
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
                        label="Enter Description:"
                        help="Enter your store description here"
                        value={ desc }
                        onChange={ onChangeDesc }
                    />
                </PanelBody>
                <PanelBody title={ 'App Store Settings' }>
                    <p><strong>Select Image:</strong></p>
                    <div className={'springoo-panel-image'}>
                        <img src= {appStoreImg} />
                    </div>
                    <MediaUpload onSelect={ onSelectAppStoreImg } type="image" value={ appStoreImg } render={ ( { open } ) => (
                        <Button className="components-button editor-post-publish-button editor-post-publish-button__button is-primary springoo-panel-btn" onClick={ open }>Upload app store logo</Button>
                    )}/>
                    <p style={ { marginTop: '20px' } }><strong>App Store link:</strong></p>
                    <RichText
                        key="App Store"
                        tagName="p"
                        placeholder="Enter app store link"
                        value={ appStoreUrl }
                        onChange={ onChangeAppStoreUrl }
                    />
                </PanelBody>
                <PanelBody title={ 'Google Play Settings' }>
                    <p><strong>Select Image:</strong></p>
                    <div className={'springoo-panel-image'}>
                        <img src= {googlePlayImg} />
                    </div>
                    <MediaUpload onSelect={ onSelectGooglePlayImg } type="image" value={ googlePlayImg } render={ ( { open } ) => (
                        <Button className="components-button editor-post-publish-button editor-post-publish-button__button is-primary springoo-panel-btn" onClick={ open }>Upload google play logo</Button>
                    )}/>
                    <p style={ { marginTop: '20px' } }><strong>Google Play Store link:</strong></p>
                    <RichText
                        key="Google Play Store"
                        tagName="p"
                        placeholder="Enter Google Play Store Link:"
                        value={ googlePlayUrl }
                        onChange={ onChangeGooglePlayUrl }
                    />
                </PanelBody>
            </InspectorControls>,
            <div className="springoo-app-wrap">
                <h2 className="widgettitle">{ title }</h2>
                <p className="springoo-app-desc">{ desc }</p>
                <ul>
                    <li>
                        <a className="app-store-url" href={appStoreUrl}>
                            <img src= {appStoreImg} />
                        </a>
                    </li>
                    <li>
                        <a className="google-play-url" href={googlePlayUrl}>
                            <img src= {googlePlayImg} />
                        </a>
                    </li>
                </ul>
            </div>
        ]);
    },

    save({ attributes }) {
        const {
            title,
            desc,
            googlePlayImg,
            googlePlayUrl,
            appStoreImg,
            appStoreUrl,
        } = attributes;


        return (
            <div className="springoo-app-wrap">
                <h2 className="widgettitle">{ title }</h2>
                <p className="springoo-app-desc">{ desc }</p>
                <ul>
                    <li>
                        <a className="app-store-url" href={appStoreUrl}>
                            <img className="app-store-img" src={appStoreImg} alt="App Store"/>
                        </a>
                    </li>
                    <li>
                        <a className="google-play-url" href={googlePlayUrl}>
                            <img className="google-play-img" src={googlePlayImg} alt="Google Play Store"/>
                        </a>
                    </li>
                </ul>
            </div>
        );
    }
});
