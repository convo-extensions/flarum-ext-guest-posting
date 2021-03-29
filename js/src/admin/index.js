import {extend} from 'flarum/common/extend';
import app from 'flarum/app';
import PermissionGrid from 'flarum/admin/components/PermissionGrid';

/* global m */

const settingsPrefix = 'guest-posting.';
const translationPrefix = 'guest-posting.admin.settings.';

function allowGuest(items, key) {
    if (!items.has(key)) {
        return;
    }

    items.get(key).allowGuest = true;
}

app.initializers.add('guest-posting', () => {
    app.extensionData.for('kilowhat-guest-posting')
        .registerSetting({
            type: 'switch',
            setting: settingsPrefix + 'enableImport',
            label: app.translator.trans(translationPrefix + 'enableImport'),
        })
        .registerSetting(function () {
            // Can't use a setting with type=number because we cannot set the default value
            return m('.Form-group', [
                m('label', app.translator.trans(translationPrefix + 'sessionLifetime')),
                m('input.FormControl', {
                    type: 'number',
                    min: 0,
                    step: 1,
                    bidi: this.setting(settingsPrefix + 'sessionLifetime', 48),
                }),
            ]);
        })
        .registerSetting(function () {
            // Can't use a simple setting because textarea type is not available
            return m('.Form-group', [
                m('label', app.translator.trans(translationPrefix + 'usernames')),
                m('textarea.FormControl', {
                    bidi: this.setting(settingsPrefix + 'usernames', ''),
                }),
            ]);
        });

    extend(PermissionGrid.prototype, 'startItems', items => {
        allowGuest(items, 'start');
        allowGuest(items, 'discussion.startWithoutApproval');
    });

    extend(PermissionGrid.prototype, 'replyItems', items => {
        allowGuest(items, 'reply');
        allowGuest(items, 'discussion.replyWithoutApproval');
        allowGuest(items, 'votePolls');
        allowGuest(items, 'fof-recaptcha.postWithoutCaptcha');
    });
}, -100);
