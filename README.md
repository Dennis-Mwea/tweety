[![Build Status](https://travis-ci.com/github/Dennis-Mwea/tweety)](https://travis-ci.com/github/Dennis-Mwea/tweety)

# Tweety

This is the repository for the "Laravel From
Scratch" [final project](https://laracasts.com/series/laravel-6-from-scratch#chapter-14) at Laracasts
with [extended functionalities](#extended-functionalities). Original repo can be
found [here](https://github.com/laracasts/Tweety).

## Installation

To run this project, you must have PHP 7 and above installed.

- You should setup a host on your web server for your local domain. For this you could also configure Laravel Homestead
  or Valet.

```bash
git clone git@github.com:TunNandaAung/tweety.git
cd tweety && composer install && npm install
php artisan tweety:install
npm run dev
```

### Step 2

Next, boot up a server and visit Tweety. If using Laravel Valet, the URL will default to `http://tweety.test`.

#### Instant Search

- For instant search, you have to create an account at [algolia](https://www.algolia.com/users/sign_up).
- Then, you have to reference your algolia app id and algolia secret key in your `.env` file as below.

```bash
MIX_ALGOLIA_APP_ID=YOUR_ALGOLIA_APP_ID
MIX_ALGOLIA_SECRET=YOUR_ALGOLIA_SECRET
```

## Extended Functionalities

1. Dynamic profile banner image and description for each user.
2. The ability to attach an image when publishing a tweet.
3. The ability to toggle a like.
4. Pop-up flash messages.
5. Interactivity with [Vuejs](https://vuejs.org/).
6. Display the number of remaining characters allowed when writing a new tweet,.
7. Allow tweets to be deleted.
8. Mentions and notifications.
9. Two level nested replies.
10. Instant search with algolia

## Mobile App

Mobile App for Tweety written in Flutter can be found [here](https://github.com/Dennis-Mwea/tweety-app).
