/**
 * Similar to filterPostMentions
 * Instead of relying on the original filter and adding just one attribute,
 * we create a new filter that will run first and return true while setting all required value
 * The default filter will run next and take care of normal mentions
 * @param tag
 * @return {boolean}
 */
export function filterPostMentions(tag) {
    const post = app.store.getById('posts', tag.getAttribute('id'));

    if (post && post.attribute('guest_username')) {
        tag.setAttribute('discussionid', post.discussion().id());
        tag.setAttribute('number', post.number());
        tag.setAttribute('displayname', post.attribute('guest_username'));

        return true;
    }
}
