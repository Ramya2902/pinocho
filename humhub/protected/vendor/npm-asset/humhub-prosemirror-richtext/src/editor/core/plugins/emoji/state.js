/*
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

import {isChromeWithSelectionBug} from "../index"

export class EmojiState {
    constructor(state, options) {
        this.state = state;
        this.provider = options.provider;
        this.provider.event.on('closed', () => {
            if(this.active) {
                const { emojiQuery } = this.state.schema.marks;
                this.view.dispatch(this.state.tr.removeMark(0, this.state.doc.nodeSize -2, emojiQuery));
            }
        }).on('focus', () => {
            this.view.focus();
        });
        this.reset();
    }

    findQueryNode() {
        return $(this.view.dom).find('[data-emoji-query]');
    }

    update(state, view) {
        this.view = view;
        this.state = state;
        const { emojiQuery } = state.schema.marks;
        const { doc, selection } = state;
        const { $from, from, to } = selection;

        this.active = doc.rangeHasMark(from - 1, to, emojiQuery);

        if (!this.active) {
            return this.reset();
        }

        let $query = this.findQueryNode();
        let $pos = doc.resolve(from - 1);

        this.queryMark = {
            start: $pos.path[$pos.path.length - 1],
            end: to
        };

        let nodeBefore = $from.nodeBefore;

        if(!nodeBefore.text.length || nodeBefore.text.length > 1) {
            this.provider.reset();
            return;
        }

        let query = nodeBefore.text.substr(1);

        if(query != this.query) {
            this.query = query;
            this.provider.query(this, $query[0]);
        }
    }

    reset() {
        this.active = false;
        this.query = null;
        if(this.view) {
            this.provider.reset();
            this.view.focus();
        }
    }

    addEmoji(item) {
        const { emoji } = this.state.schema.nodes;
        const { emojiQuery } = this.state.schema.marks;

        const nodes = [emoji.create({
            'data-name': item.name,
            alt: item.alt,
            src: item.src
        }, null)];


        let tr = this.state.tr
            .removeMark(0, this.state.doc.nodeSize -2, emojiQuery)
            .replaceWith(this.queryMark.start, this.queryMark.end, nodes);

        if(isChromeWithSelectionBug) {
            document.getSelection().empty();
        }

        this.view.dispatch(tr);
        this.view.focus();
    }
}

let endsWith = function(string, suffix) {
    return string.indexOf(suffix, string.length - suffix.length) !== -1;
};