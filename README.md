# Anonymous guest posting
### Anonymous, permission-based guest posting extension for Flarum

![Giphy - hide GIF](https://media2.giphy.com/media/COYGe9rZvfiaQ/giphy.gif?cid=7175f6ac2w2ple7hor4yiac55sbx3tcjdxxe0swvvudgh31p&rid=giphy.gif&ct=g)

# What
The extension lets unregistered users comment on discussion, create new discussions, and vote on polls. It is fully compatible with the latest version of Flarum and has been tested with dozens of extensions and large-scale communities. The extension was developed by @clarkwinkelmann, a core developer of Flarum.


# How

### 🔒 Permission-based
Once you install the extension, you can configure its functionality from the Permissions panel (and limit it by tags). You can let unregistered users:
- Start discussions
- Start discussions without approval
- Reply to discussions
- Reply to discussions without approval
- Vote on polls
- Change a vote

![](https://i.imgur.com/cys1e84h.jpg)

If you don't select "without approval" options, anonymous posts and discussions will not be shown until approved by a moderator.

### 👤 Migrate posts into a newly-created account

Once the user has made an anonymous post, he'd be shown a message on the homepage encouraging him to sign up and attribute his posts to his newly-registered account (works by using cookies). How cool is that?! 🤯

### 🌎 Translatable

All strings in this extension can be translated through the [Linguist](https://discuss.flarum.org/d/7026-linguist-customize-translations-with-ease) extension

# [Try it yourself! (Demo)](https://convo-extensions-demo.convo.co.il/d/1-anonymous-guest-posting)

Once you've purchased the extension, run the following command to install:

```
composer require convo-extensions/flarum-ext-guest-posting
```

If you need any help, shoot us an email at flarum@overview.co.il
