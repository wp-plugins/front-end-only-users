=== Plugin Name ===
Contributors: Rustaurius 
Tags: membership, WordPress members, user management, market segmentation, personalization, front-end users, custom field registration, custom redirects, custom registration, custom registration form, custom registration page, custom user profile, customize profile, edit profile, extra user fields, front-end edit profile, front-end login, front-end register, front-end registration, front-end user listing, front-end user registration, profile builder, registration, registration page, user custom fields, user email, user listing, user login, user profile, user profile page, User Registration, user registration form, user-fields, password, profile, email, custom fields, premium content, PureCSS
Requires at least: 3.5.0
Tested up to: 3.8
License: GPLv3
License URI:http://www.gnu.org/licenses/gpl-3.0.html

Allow visitors to sign up as users on the front-end of your website only. Completely customizable, allows personalization of a website.

== Description ==

Allow visitors to sign up as users on the front-end of your website only. Completely customizable, allows personalization of a website.

Use shortcodes to insert registration, login or profile editing forms on any page of your website.
Users are created in tables separate from the main WordPress user tables so that they have no access to the back-end of your site.
You create different fields for members to fill out, and can customize content based on their profiles (location, gender, language preference, etc.)

[youtube http://www.youtube.com/watch?v=3HI8-t8a1wA]

Key Features:

* Customizable membership fields 
* Supports all input types for user fields
* UTF8 support
* Different membership levels, content can be restricted to certain levels
* PureCSS-styled forms for elegant design
* Allows personalization of your site with the [user-data] shortcode
* Options page lets you control how long a user remains logged in
* User input-based redirects; send user groups to different pages after login (see FAQ)

**For all of the plugin shortcodes, please see the FAQ page**

This plugin creates a cookie to store login information. 

Please head to the "Support" tab to report errors or make suggestions.
Demo videos will be posted as soon as they are available.

== Installation ==

1. Upload the `front-end-only-users` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place "[register]" on the page where you want your registration form to display
4. Place "[login]", "[logout]" and "[edit-profile]" shortcodes on pages as applicable
5. All four of the shortcodes accept an attribute, 'redirect_page', that lets you send users to a different page after submitting the form

Tutorial Part 1
[youtube http://www.youtube.com/watch?v=9WE1ftZBlPw]

Tutorial Part 2
[youtube http://www.youtube.com/watch?v=viF7-yPY4H4]

--------------------------------------------------------------

- The user registration form can be customized from the admin panel, under the "Front-End Users" tab.
- Content can be restricted to users who are logged in using the [restricted][/restricted] tag. 
- You can further restrict content to a subset of users by adding "field_name" and "field_value" attributes to the restricted shortcode.
- For example, "[restricted field_name='Country' field_value='Canada']This is Canadian content.[/restricted]" would only display the line "This is Canadian content." to those who have put their "Country" as "Canada" on their user profile.
- You can also personalize your site using the [user-data] tag.
- By default the tag will return the user's Username, but can also display any other field (ex. [user-data field_name='First Name'])


== Frequently Asked Questions ==

= How do I add fields for my users to fill out? =

On the admin page, go to the "Fields" tab. 

= What's the complete list of plugin shortcodes? =

* Register Form: [register]<br />
* Login Form: [login]<br />
* Logout Form: [logout]<br />
* Edit Profile Form: [edit-profile]<br />
* Edit Account Information: [account-details]<br />
* Restriced Content: [restricted][/restricted]<br />
* Inserting User Information (see below): [user-data]<br />
* User Search Form: [user-search]<br />
* User List: [user-list]<br />

= How do I redirect based on a user field? =

You need to add the following attributes to your [login] or [register] shortcodes:
* redirect_field: the field the redirect is based off of (ex. Gender)
* redirect_array_string: a comma separated list of pages to redirect to (ex. Male => http://ManPage.com, Female => http://WomanPage.com)

= How do I display a user's first name on a page? =

You can use the [user-data field_name='First Name'] shortcode, assuming that you called your field "First Name" for a user's first name.

= How do I restrict content to vistors who have logged in? =

Content can be restricted using the [restricted][/restricted] tag. Any content between the opening and closing tags will only be visible to those who are logged in. 

= Tutorial Part 1 =
[youtube http://www.youtube.com/watch?v=9WE1ftZBlPw]

= Tutorial Part 2 =
[youtube http://www.youtube.com/watch?v=viF7-yPY4H4]


== Screenshots ==

1. Sample registration page
2. Example of the edit profile page
3. Page showing user data
4. Example of a restricted page
5. The admin area

== Changelog ==
= 1.6 = 
- Tiny change

= 1.5 =
- Added "sneak peak" attributes to the [restricted] shortcode; you can now set attributes for either sneak_peak_characters or sneak_peak_words within the shortcode
- Added the ability to redirect based on a user field; to use it, see the plugin page

= 1.4 =
- Fixed a naming conflict error

= 1.3 =
- Shortcodes inside of [restricted][/restricted] tags should now work
- Added 3 new methods to the "EWD_FEUP" class to access User_ID, Username and any custom field
- Fixed a bug that prevented e-mail settings from being saved
- Fixed a bug that was causing a conflict with the options of a handful of other plugins

= 1.2 =
- Fixed a database error for new installs

= 1.1 = 
- Fixed an error with sign-up e-mails
- Fixed an error with "Admin Approval"

= 1.0 = 
- Added an "Admin Approval" of users option
- Added "Sign-up Emails" tab, options and message customization
- Added "login_page" attribute to the "restricted" shortcode
- Added an "EWD_FEUP" class, that let's template designers check whether a user is logged in or not
- Added a "file" field type, so admins can have users upload files as one of the fields
- Created a "Custom CSS" option box, so forms can be styled from the admin panel
- Added a "no_message" attribute to the "restricted" shortcode that won't display a message if a user is not logged in
- Created a "[user-list]" shortcode
- Created a "[user-search]" shortcode

= 0.5 = 
- YouTube tutorial videos added
- Fixed redirection bugs
- Fixed date and datetime input fields
- Fixed bug where users could register with the same username
- Fixed two small shortcode bugs

= 0.4 =
- Fixed an admin display bug

= 0.3 =
- Fixed a couple of small bugs

= 0.2 =
- Fixed a number of bugs that made plugin unusable

= 0.1 =
- Initial beta version. Please make comments/suggestions in the "Support" forum.

== Upgrade Notice ==

- The bugs that make plugin impossible to use for most users have been fixed

