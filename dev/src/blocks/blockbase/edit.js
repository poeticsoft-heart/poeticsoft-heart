const { __ } = wp.i18n;
const { useBlockProps, InspectorControls, RichText } = wp.blockEditor;
const { PanelBody, ToggleControl } = wp.components;
const { Fragment } = wp.element;
import './edit.scss';

export default function Edit( { attributes, setAttributes } ) {
    const { message, showIcon } = attributes;

    const blockProps = useBlockProps();

    return (
        <Fragment>
            <InspectorControls>
                <PanelBody title={ __( 'Configuración Base', 'poeticsoft-heart-theme' ) }>
                    <ToggleControl
                        label={ __( 'Mostrar Icono', 'poeticsoft-heart-theme' ) }
                        checked={ showIcon }
                        onChange={ ( value ) => setAttributes( { showIcon: value } ) }
                    />
                </PanelBody>
            </InspectorControls>

            <div { ...blockProps }>
                <div className="psh-block-content">
                    { showIcon && <span className="psh-icon">🚀</span> }
                    <RichText
                        tagName="p"
                        value={ message }
                        onChange={ ( value ) => setAttributes( { message: value } ) }
                        placeholder={ __( 'Escribe un mensaje...', 'poeticsoft-heart-theme' ) }
                    />
                </div>
            </div>
        </Fragment>
    );
}
