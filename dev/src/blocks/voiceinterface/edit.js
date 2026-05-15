import './edit.scss';

export default function Edit({ attributes, setAttributes }) {
    const { RichText, InspectorControls, useBlockProps } = wp.blockEditor;
    const { PanelBody, TextControl } = wp.components;
    
    const { placeholderText } = attributes;
    const blockProps = useBlockProps({
        className: 'psh-voiceinterface-editor'
    });

    return (
        <>
            { InspectorControls && (
                <InspectorControls>
                    <PanelBody title="Ajustes de Interfaz">
                        <TextControl
                            label="Texto del botón"
                            value={placeholderText}
                            onChange={(val) => setAttributes({ placeholderText: val })}
                        />
                    </PanelBody>
                </InspectorControls>
            ) }

            <div { ...blockProps }>
                <div className="psh-voice-orb-preview">
                    <div className="psh-orb"></div>
                </div>
                { RichText && (
                    <RichText
                        tagName="p"
                        className="psh-voice-label"
                        value={placeholderText}
                        onChange={(val) => setAttributes({ placeholderText: val })}
                        placeholder="Escribe el mensaje de bienvenida..."
                    />
                ) }
                <div className="psh-voice-editor-badge">Voice Interface Block</div>
            </div>
        </>
    );
}


