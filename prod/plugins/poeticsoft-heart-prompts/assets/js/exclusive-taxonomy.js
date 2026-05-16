(function(wp) {
    const { subscribe, select, dispatch } = wp.data;
    const { __ } = wp.i18n;

    // Obtener los IDs de las ramas principales pasados desde PHP
    const branchConfig = window.pshPromptConfig || {};
    const systemId = parseInt(branchConfig.system_id);
    const userId = parseInt(branchConfig.user_id);
    const termMap = branchConfig.term_map || {}; // { termId: rootId }

    let lastTerms = [];
    let isProcessing = false;

    // Suscribirse a los cambios en el editor
    const unsubscribe = subscribe(() => {
        const editor = select('core/editor');
        if (!editor || isProcessing) return;

        // Solo actuar si estamos en el CPT correcto
        const postType = editor.getEditedPostAttribute('type');
        if (postType !== 'psh_prompt') return;

        const currentTerms = editor.getEditedPostAttribute('psh_prompt_type') || [];

        // Si no han cambiado los términos, no hacemos nada
        if (JSON.stringify(currentTerms) === JSON.stringify(lastTerms)) return;

        // Detectar si se ha añadido un término nuevo
        const addedTerm = currentTerms.find(id => !lastTerms.includes(id));
        
        if (addedTerm) {
            const newBranchRoot = termMap[addedTerm];
            
            // Si el nuevo término pertenece a una rama
            if (newBranchRoot) {
                // Buscar si ya había términos de la OTRA rama
                const otherBranchTerms = lastTerms.filter(id => {
                    const rootId = termMap[id];
                    return rootId && rootId !== newBranchRoot;
                });

                if (otherBranchTerms.length > 0) {
                    const branchName = newBranchRoot === systemId ? 'System' : 'User';
                    const confirmMsg = __(
                        `Has seleccionado un término de la rama "${branchName}". Esto eliminará toda la categorización previa de la otra rama. ¿Deseas continuar?`,
                        'poeticsoft-heart-prompts'
                    );

                    if (window.confirm(confirmMsg)) {
                        isProcessing = true;
                        // Quedarnos solo con el nuevo término (y otros de su misma rama si los hubiera)
                        const cleanTerms = currentTerms.filter(id => termMap[id] === newBranchRoot);
                        dispatch('core/editor').editPost({ psh_prompt_type: cleanTerms });
                        lastTerms = cleanTerms;
                        setTimeout(() => { isProcessing = false; }, 50);
                        return;
                    } else {
                        isProcessing = true;
                        // Revertir: Volver a los términos anteriores
                        dispatch('core/editor').editPost({ psh_prompt_type: lastTerms });
                        setTimeout(() => { isProcessing = false; }, 50);
                        return;
                    }
                }
            }
        }

        lastTerms = currentTerms;
    });

})(window.wp);
