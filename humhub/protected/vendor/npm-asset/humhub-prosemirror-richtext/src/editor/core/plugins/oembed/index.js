/*
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */
import {schema} from './schema'
import {oembed_plugin} from './markdownit_oembed'
import {$node} from '../../util/node'

const oembed = {
    id: 'oembed',
    schema: schema,
    init: (context) => {
        context.event.on('linkified', (evt, urls) => {
            let doc = context.editor.view.state.doc;
            if($node(doc).find('oembed').size() >= context.getPluginOption('oembed', 'max', 5)) {
                return;
            }

            humhub.require('oembed').load(urls).then((result) => {
                $.each(result, function(url, oembed) {
                    let $links = $node(context.editor.view.state.doc).find().mark('link').where((node) => {
                        return $node(node).getMark('link').attrs.href === url;
                    });

                    $links.replaceWith(context.schema.nodes.oembed.create({href:url}), context.editor.view);

                    // We only allow a single oembed per copy/paste
                    return false;
                })
            });

        });
    },
    registerMarkdownIt: (markdownIt) => {
        markdownIt.inline.ruler.before('link','oembed', oembed_plugin);

        markdownIt.renderer.rules.oembed = function(token, idx) {
            let oembed = token[idx];
            let href = markdownIt.utils.escapeHtml(oembed.attrGet('href'));
            let $oembed = $('[data-oembed="'+href+'"]');

            if(!$oembed.length) {
                return '<a href="'+href+'" target="_blank" rel="noopener">'+href+'</a>';
            }

            $oembed = $oembed.clone();
            return $oembed.html();
        };
    }
};

export default oembed;
