
Christian Webber's CptS 483 Final Project!


- MVC Framework: CodeIgniter
- Other Libraries: jQuery, jQueryUI, DebouncedResize

FinalDB.sql builds the database for the Blog and gives it some example data.

Features
- 1) Page Creation/Editing/Deletion for authenticated users with valid rank
+++ index shows 10 latest posts (latest first)
+++ 'Posts' page shows all posts (latest first)
- 2) Page Commenting for authenticated users (latest first)
- AJAX Functionality:
+++ 3/4) Login/Account Creation system.
++++++ Can login or create an account from any page and at any time.
++++++ Login info (account settings, etc.) is stored in a cookie (aside from password!)
++++++ Roles: Guest (can view posts, comments, and games), User (3: same as Guest and can post comments), Moderator (2: same as User and can moderate comments/posts), Poster (1: same as User and can post new posts), Admin (0: can do everything!)

Bonus Features
- 1) Theme switching. Easily switch between a primary and an alternate theme.
- 2) Upvote/downvote system for pages and comments
- 3) Game Support
+++ 'Classic' HTML engine (no auto-included header or footer) or 'ApolloFramework' (auto-included game header and foot)
- 4) Administration for authenticated users with valid rank
+++ Page Administration: allows for editing posts, deleting posts, or adding new posts.
+++ User Administration: allows for editing users, deleting users, or adding new users.
+++ Comment Administration: allows to delete comments (on the post they are posted to)