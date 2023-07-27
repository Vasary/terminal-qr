import mermaid from 'mermaid';

mermaid.initialize({
    startOnLoad: false,
    theme: 'dark',
    themeVariables: {
        fontSize: '14px',
        textColor: '#ffffff'
    }
});

(async () => await mermaid.run())()
