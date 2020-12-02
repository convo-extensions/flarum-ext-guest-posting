# Guest Posting

## Guest session lifetime

How long until an inactive guest session expires.
Defaults to 48 hours.

During this time, the same random guest username will be used for all new posts.
The discussion and post IDs are kept in memory and can be claimed during Sign Up if **Enable importation** is enabled.

Once the session expires, the user will get a new random guest username and won't be able to claim their old posts for a new account anymore.

## Random usernames

The usernames are picked at random when a new guest posts.
Usernames must be entered separated by a newline.

There is no limit to the number of usernames, however the field is limited to 64KiB of data due to the Flarum settings max size (MySQL `TEXT` column).

## Known limitations

- Doesn't work with tag-specific permissions
- If approval is required, guests won't see their own post after publication
- Guests cannot be mentioned
- Homepage shows `[deleted]` as the author or last poster if it's a guest

## Installation
- Create a workbench folder
- Add this to composer.json under `repositories`: `{"type": "path","url": "workbench/*"}`
- Upload files and run `composer require alter/flarum-ext-guest-posting:*@dev`
- If there's an issue with the polls extension, run `composer require fof/polls:^0.1.5`
