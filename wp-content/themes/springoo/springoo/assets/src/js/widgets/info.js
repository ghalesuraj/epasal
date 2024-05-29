/* eslint-disable */
/* global springoo_main */
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
    Button,
    SelectControl,
    TextareaControl,
    Icon
} = wp.components;

registerBlockType('springoo/info', {
    title: 'Store Info',
    description: 'Springoo Store info widget',
    icon: {
        src: <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 22.75C6.07 22.75 1.25 17.93 1.25 12C1.25 6.07 6.07 1.25 12 1.25C17.93 1.25 22.75 6.07 22.75 12C22.75 17.93 17.93 22.75 12 22.75ZM12 2.75C6.9 2.75 2.75 6.9 2.75 12C2.75 17.1 6.9 21.25 12 21.25C17.1 21.25 21.25 17.1 21.25 12C21.25 6.9 17.1 2.75 12 2.75Z" fill="#000000"/>
            <path d="M12 13.75C11.59 13.75 11.25 13.41 11.25 13V8C11.25 7.59 11.59 7.25 12 7.25C12.41 7.25 12.75 7.59 12.75 8V13C12.75 13.41 12.41 13.75 12 13.75Z" fill="#000000"/>
            <path d="M12 16.9999C11.87 16.9999 11.74 16.9699 11.62 16.9199C11.5 16.8699 11.39 16.7999 11.29 16.7099C11.2 16.6099 11.13 16.5099 11.08 16.3799C11.03 16.2599 11 16.1299 11 15.9999C11 15.8699 11.03 15.7399 11.08 15.6199C11.13 15.4999 11.2 15.3899 11.29 15.2899C11.39 15.1999 11.5 15.1299 11.62 15.0799C11.86 14.9799 12.14 14.9799 12.38 15.0799C12.5 15.1299 12.61 15.1999 12.71 15.2899C12.8 15.3899 12.87 15.4999 12.92 15.6199C12.97 15.7399 13 15.8699 13 15.9999C13 16.1299 12.97 16.2599 12.92 16.3799C12.87 16.5099 12.8 16.6099 12.71 16.7099C12.61 16.7999 12.5 16.8699 12.38 16.9199C12.26 16.9699 12.13 16.9999 12 16.9999Z" fill="#000000"/>
        </svg>
    },
    category: 'springoo',

    // custom attributes
    attributes: {
        lightImgUrl: {
            type: 'string',
            default: '',
        },
        darkImgUrl: {
            type: 'string',
            default: '',
        },
        desc: {
            type: 'string',
            source: 'html',
            selector: 'p'
        },
        socialArr: {
            type: 'array',
            source: 'query',
            default: [{index: 0}],
            selector: '.social_wrap',
            query: {
                index: {
                    attribute: 'data-index',
                    source: 'attribute',
                    selector: '.index',
                },
                social_name: {
                    source: 'attribute',
                    selector: 'i',
                    attribute: 'class'
                },
                social_link: {
                    source: 'attribute',
                    selector: 'a',
                    attribute: 'href'
                },
            }
        },
        phone: {
            source: 'html',
            selector: '.info_phone',
        },
        email: {
            source: 'html',
            selector: '.info_email',
        },
    },

    edit({ attributes, setAttributes }) {
        const {
            lightImgUrl,
            darkImgUrl,
            desc,
            socialArr,
            phone,
            email
        } = attributes;

        // custom functions

        function onSelectLightImgUrl(newImgUrl){
            setAttributes({ lightImgUrl: newImgUrl.sizes.full.url });
        }
        function onSelectDarkImgUrl(newImgUrl){
            setAttributes({ darkImgUrl: newImgUrl.sizes.full.url });
        }
        function onChangeDesc( newDesc ) {
            setAttributes( { desc: newDesc } );
        }

        function _toConsumableArray(arr) {
            if ( Array.isArray(arr) ) {
                const arr2 = Array(arr.length);
                for ( let i = 0; i < arr.length; i++ ) {
                    arr2[i] = arr[i];
                }
                return arr2;
            } else {
                return Array.from(arr);
            }
        }

        let socialIcons = socialArr.sort(function (a, b) {
            return a.index - b.index;
        }).map(function (item) {
            return [
                <li className='social_wrap'>
                    <div className="social_content">
                        <SelectControl
                            label="Select Social Profile"
                            options={ [
                                { label: '--Select Social Profile--', value: 'none' },
                                { label: 'Facebook', value: 'si si-bold-facebook' },
                                { label: 'Twitter', value: 'si si-bold-twitter' },
                                { label: 'Youtube', value: 'si si-bold-youtube' },
                                { label: 'Instagram', value: 'si si-bold-instagram' },
                                { label: 'Linkedin', value: 'si si-bold-linkedin' },
                                { label: 'Pinterest', value: 'si si-bold-pinterest' },
                                { label: 'Dribbble', value: 'si si-bold-dribbble' },
                            ] }
                            value={item.social_name}
                            onChange={(social_name) => {
                                let newObject = Object.assign({}, item, {
                                    social_name: social_name
                                });
                                setAttributes({
                                    socialArr: [].concat(_toConsumableArray(socialArr.filter(function (element) {
                                        return element.index != item.index;
                                    })), [newObject])
                                });
                            }}
                        />
                        <RichText
                            tagName='p'
                            piaceholder='Social links'
                            value={item.social_link}
                            onChange={(social_link) => {
                                let newObject = Object.assign({}, item, {
                                    social_link: social_link
                                });
                                setAttributes({
                                    socialArr: [].concat(_toConsumableArray(socialArr.filter(function (element) {
                                        return element.index != item.index;
                                    })), [newObject])
                                });
                            }}
                        />
                    </div>
                    <Button icon='trash' className="springoo-close-btn" onClick={() => {
                        let newItem = socialArr.filter(function (element) {
                            return element.index != item.index;
                            }).map(function (t) {
                                if (t.index > item.index) {
                                    t.index -= 1;
                                }
                                return t;
                            });
                            setAttributes({ socialArr: newItem });
                        }} >
                    </Button>
                </li>
            ];
        });

        function onChangePhone( newPhone ) {
            setAttributes( { phone: newPhone } );
        }
        function onChangeEmail( newEmail ) {
            setAttributes( { email: newEmail } );
        }

        return ([
            <InspectorControls style={ { marginBottom: '40px' } }>
                <PanelBody title={ 'Info Settings' }>
                    <p><strong>Select Dark Image:</strong></p>
                    <div className={'springoo-panel-image'}>
                        <img src= {darkImgUrl} />
                    </div>
                    <MediaUpload onSelect={ onSelectDarkImgUrl } type="image" value={ darkImgUrl } render={ ( { open } ) => (
                            <Button className="components-button editor-post-publish-button editor-post-publish-button__button is-primary springoo-panel-btn" onClick={ open } style={ { marginBottom: '20px' } }>Upload logo</Button>
                    )}/>
                    <p><strong>Select Light Image:</strong></p>
                    <div className={'springoo-panel-image'}>
                        <img src= {lightImgUrl} />
                    </div>
                    <MediaUpload onSelect={ onSelectLightImgUrl } type="image" value={ lightImgUrl } render={ ( { open } ) => (
                            <Button className="components-button editor-post-publish-button editor-post-publish-button__button is-primary springoo-panel-btn" onClick={ open } style={ { marginBottom: '20px' } }>Upload logo</Button>
                    )}/>
                    <TextareaControl
                        label="Enter Description:"
                        value={ desc }
                        onChange={ onChangeDesc }
                    />
                    <TextareaControl
                        label="Enter Phone NUmber:"
                        value={ phone }
                        onChange={ onChangePhone }
                    />
                    <TextareaControl
                        label="Enter Your Email:"
                        value={ email }
                        onChange={ onChangeEmail }
                    />
                </PanelBody>
                <PanelBody title={ 'Social Media' }>
                    <ul>
                        {socialIcons}
                    </ul>
                    <div className="springoo-block-btn springoo-block-add-btn">
                        <Button
                            className="components-button is-primary social-profile-add"
                            onClick={() => {
                                return setAttributes({
                                    socialArr: [].concat(_toConsumableArray(attributes.socialArr), [{
                                        index: attributes.socialArr.length,
                                        social_content: '',
                                    }])
                                });
                            }} >Add Social Profiles
                        </Button>
                    </div>
                </PanelBody>
            </InspectorControls>,
            <div className="springoo-info-wrap">
                <div className="springoo-panel-image">
                    <img src= {darkImgUrl} className="dark-logo"/>
                    <img src= {lightImgUrl} className="light-logo" style={{ display: 'none' }}/>
                </div>
                <p className="springoo-info-desc">{ desc }</p>
                <ul className="springoo-info-social-icon">
                    {socialIcons}
                </ul>
                <div className="springoo-block-btn springoo-block-add-btn">
                    <Button
                        className="components-button is-primary social-profile-add"
                        onClick={() => {
                            return setAttributes({
                                socialArr: [].concat(_toConsumableArray(attributes.socialArr), [{
                                    index: attributes.socialArr.length,
                                    social_content: '',
                                }])
                            });
                        }} >Add Social Profiles
                    </Button>
                </div>
            </div>
        ]);
    },

    save({ attributes }) {
        const {
            lightImgUrl,
            darkImgUrl,
            desc,
            socialArr,
            phone,
            email
        } = attributes;

        let socialIcons = socialArr.map(function (item) {
            return <li className='social_wrap' >
                <span className="index" data-index={item.index} aria-hidden="true"></span>
                <a href={item.social_link} className="social_link">
                    <i className={item.social_name}></i>
                    <span className="sr-only">{item.social_name}</span>
                </a>
            </li>
        });

        return (
            <div className="springoo-info-wrap" >
                <img src= { darkImgUrl } alt="logo" className="dark-logo"/>
                <img src= { lightImgUrl } alt="logo" className="light-logo"/>
                <p className="springoo-info-desc">{ desc }</p>
                { socialIcons.length > 0 &&
                    <ul className="springoo-info-social-icon">
                        { socialIcons }
                    </ul>
                }
                { phone.length > 0 &&
                    <div className="springoo-info-phone">
                        <i className="si si-thin-call"></i>
                        <div className="springoo-info-phone-content">
                            <span>Call Us:</span>
                            <p className="info_phone_wrap"><a href={ 'tel:' + phone }><span className="info_phone">{phone}</span></a></p>
                        </div>
                    </div>
                }

                { email.length > 0 &&
                    <div className="springoo-info-email">
                        <i className="si si-thin-mail"></i>
                        <div className="springoo-info-email-content">
                            <span>Email Us:</span>
                            <p className="info_phone_wrap"><a href={ 'mailto:' + email }><span className="info_email">{email}</span></a></p>
                        </div>
                    </div>
                }
            </div>
        );
    }
});
