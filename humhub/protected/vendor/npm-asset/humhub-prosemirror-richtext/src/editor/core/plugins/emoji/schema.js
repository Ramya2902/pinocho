import twemoji from "twemoji"

const schema = {
    nodes: {
        emoji:  {
            attrs: {
                class: {default: 'emoji'},
                draggable: {default: 'false'},
                width: {default: '16'},
                height: {default: '16'},
                'data-name': {default: null},
                alt: {default: null},
                src: {default: null},
            },
            inline: true,
            group: "inline",
            parseDOM: [{
                tag: "img.emoji", getAttrs: function getAttrs(dom) {
                    return {
                        src: dom.getAttribute("src"),
                        alt: dom.getAttribute("alt"),
                        'data-name': dom.getAttribute('data-name')
                    }
                }
            }],
            toDOM: function toDOM(node) {
                return ['img', node.attrs]
            },
            parseMarkdown:  {
                node: "emoji", getAttrs: function (tok) {
                    let $dom = $(twemoji.parse(tok.content));
                    return ({
                        'data-name': tok.markup,
                        alt: $dom.attr('alt'),
                        src: $dom.attr('src')
                    })
                }
            },
            toMarkdown: (state, node) => {
                let result;

                if(!node.attrs['data-name']) {
                    result = (state.alt) ? state.esc(state.alt) : '';
                } else {
                    result = ':'+state.esc(node.attrs['data-name'])+':';
                }

                state.write(result)
            }
        }
    },
    marks: {
        emojiQuery: {
            excludes: "_",
            inclusive: true,
            parseDOM: [
                { tag: 'span[data-emoji-query]' }
            ],
            toDOM(node) {
                return ['span', {
                    'data-emoji-query': true,
                }];
            }
        }
    }
};

export {schema};

