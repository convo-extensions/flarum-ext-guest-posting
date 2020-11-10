import app from 'flarum/app';
import BaseSettingsModal from 'flarum/components/SettingsModal';
import Switch from 'flarum/components/Switch';

/* global m */

const settingsPrefix = 'guest-posting.';
const translationPrefix = 'guest-posting.admin.settings.';

export default class SettingsModal extends BaseSettingsModal {
    title() {
        return app.translator.trans(translationPrefix + 'title');
    }

    form() {
        return [
            m('.Form-group', [
                m('label', Switch.component({
                    state: this.setting(settingsPrefix + 'enableImport')() === '1',
                    onchange: value => {
                        this.setting(settingsPrefix + 'enableImport')(value ? '1' : '0');
                    },
                }, app.translator.trans(translationPrefix + 'enableImport'))),
            ]),
            m('.Form-group', [
                m('label', app.translator.trans(translationPrefix + 'sessionLifetime')),
                m('input.FormControl', {
                    type: 'number',
                    min: 0,
                    step: 1,
                    bidi: this.setting(settingsPrefix + 'sessionLifetime', 48),
                }),
            ]),
            m('.Form-group', [
                m('label', app.translator.trans(translationPrefix + 'usernames')),
                m('textarea.FormControl', {
                    bidi: this.setting(settingsPrefix + 'usernames', ''),
                }),
            ]),
        ];
    }
}
