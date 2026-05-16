(function(wp) {
    if (!wp || !wp.plugins || !wp.editPost || !wp.element || !wp.components || !wp.data || !wp.i18n || !wp.blocks) {
        return;
    }

    const { registerPlugin } = wp.plugins;
    const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
    const { PanelBody, Button, Spinner } = wp.components;
    const { useState, useEffect, createElement, Fragment } = wp.element;
    const { useSelect, dispatch } = wp.data;
    const { __ } = wp.i18n;

    const PromptOptimizerSidebar = () => {
        const [isOptimizing, setIsOptimizing] = useState(false);
        const [feedbackIndex, setFeedbackIndex] = useState(0);
        
        const feedbackMessages = [
            __('Analizando estructura...', 'poeticsoft-heart-prompts'),
            __('Invocando a Gemini 3.1...', 'poeticsoft-heart-prompts'),
            __('Diseñando contexto...', 'poeticsoft-heart-prompts'),
            __('Estructurando instrucciones...', 'poeticsoft-heart-prompts'),
            __('Refinando restricciones...', 'poeticsoft-heart-prompts'),
            __('Dando forma editorial...', 'poeticsoft-heart-prompts'),
            __('Casi listo...', 'poeticsoft-heart-prompts')
        ];

        // Efecto para rotar los mensajes de feedback
        useEffect(() => {
            let interval;
            if (isOptimizing) {
                interval = setInterval(() => {
                    setFeedbackIndex((prev) => (prev + 1) % feedbackMessages.length);
                }, 2500);
            } else {
                setFeedbackIndex(0);
            }
            return () => clearInterval(interval);
        }, [isOptimizing]);

        const postType = useSelect((select) => {
            return select('core/editor').getEditedPostAttribute('type');
        }, []);

        const handleOptimize = async () => {
            const currentBlocks = wp.data.select('core/editor').getBlocks();
            const currentContent = wp.blocks.serialize(currentBlocks);
            setIsOptimizing(true);

            try {
                const response = await fetch('/wp-json/psh/v1/content/optimize-prompt', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': wpApiSettings.nonce
                    },
                    body: JSON.stringify({ prompt: currentContent })
                });
                const result = await response.json();
                if (result.success && result.data.html) {
                    const newBlocks = wp.blocks.parse(result.data.html);
                    dispatch('core/editor').resetBlocks(newBlocks);
                } else {
                    alert(__('Error al optimizar: ', 'poeticsoft-heart-prompts') + (result.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Poeticsoft AI Error:', error);
                alert(__('Error de conexión.', 'poeticsoft-heart-prompts'));
            } finally {
                setIsOptimizing(false);
            }
        };

        if (postType !== 'psh_prompt') return null;

        const sidebarName = "psh-prompt-optimizer-sidebar";
        const sidebarTitle = __('Poeticsoft AI', 'poeticsoft-heart-prompts');

        return createElement(
            Fragment,
            {},
            createElement(PluginSidebarMoreMenuItem, { target: sidebarName, icon: "admin-appearance" }, sidebarTitle),
            createElement(
                PluginSidebar,
                { name: sidebarName, title: sidebarTitle, icon: "admin-appearance" },
                createElement(
                    PanelBody,
                    { title: __('Optimización de Prompt', 'poeticsoft-heart-prompts') },
                    createElement('p', null, __('Usa la IA de Poeticsoft para mejorar tu prompt.', 'poeticsoft-heart-prompts')),
                    createElement(
                        Button,
                        {
                            isPrimary: true,
                            isLarge: true,
                            onClick: handleOptimize,
                            disabled: isOptimizing,
                            style: { width: '100%', justifyContent: 'flex-start', paddingLeft: '15px' }
                        },
                        isOptimizing 
                            ? [
                                createElement(Spinner, { key: 'opt-spinner', style: { marginRight: '10px' } }), 
                                createElement('span', { key: 'opt-text' }, feedbackMessages[feedbackIndex])
                              ]
                            : __('Optimizar con IA', 'poeticsoft-heart-prompts')
                    )
                )
            )
        );
    };

    registerPlugin('psh-prompt-optimizer', {
        render: PromptOptimizerSidebar
    });

})(window.wp);
