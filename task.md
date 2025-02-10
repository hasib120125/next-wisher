meeting: 12/Fab/2023 at 11:00PM
Meeting: 001;

✅1::Add translator to Category and Contry module (20min) pass true with translate function wehn dynamic data.
    ✅--Add translate helper function to Category and Contry module.

✅2::Contry module in dashboard (10min)
    ✅--Add pagination component to contry module like Users Active component

✅3::On Users > Active component (30min)
    ✅--Implement datatable
    ✅--Soft delete
    ✅--Restore

✅::On Talent > Login > Video upload issue. (1H)
    ✅-- Upload 5s-10s video.

✅4::Settings module (1D)
    -- ✅English, Français, Español column (settings(additional))
    -- ✅Basic configuration (settings(additional))
    -- ✅Page title, slug, Description(text editor) (settings(additional))
Total: 1 Day;

Next meeting: 13/Fab/2023 at 11:00PM
-----------------------------------------
Meeting: 002;

*****✅::Table no data found Alert component
2::✅Page module 1hour
    -- ✅Page edit component
    -- ✅Page Delete
    -- ✅Max 2 page create.

3::✅User password change 40min
    --✅ make password changer component for user and talent
    --✅ Password changer component put on root of AdminDashboard folder.
    --✅ make country column to country_id and make relation. And pass contry id when register and store.

4::✅On User table 2min
    -- ✅add column name status
    -- ✅add column to user table named file_access

5::✅T-Application module 1hour
    -- ✅add datatable
    -- ✅user approve or reject
    -- ✅if approve show Verified instad of video open button.
    -- ✅datatable on application declined list.

6::✅On talent module 30min
    -- ✅onclick eye button navigate to the user details page.
    -- ✅soft delete, restore
Total: 1 Day;

Next meeting: 14/Fab/2023 at 11:00PM
-----------------------------------------
Meeting: 003;

1::✅Render category list on offcanvas order by desc.. (scrollable) 10min
2::✅Render talents uploaded video which is selected by admin. 2.5hour
    -- ✅first time, render first 2 category and related video.
    -- ✅on next and prev arrow click, Show next 2 category and related video.

3:: ✅user dashboard>account module. 30min
    -- ✅personal information.(disable email)
    -- ✅password change
    -- ✅account soft delete

4:: ✅talent dashboard>account module 5min
    -- ✅personal information.(disable email)
    -- ✅password change
    -- ✅account soft delete
    -- ✅rename email name to email

5:: ✅talent dashboard>profile setup module 35min
    -- ✅replace video
    -- ✅change category
    -- ✅when video selected, show Close icon component.

6:: ✅By clicking talents navigate to talents profile 50min
    -- ✅talent profile render talents information, video.
    -- ✅user follow talents, unfollow talents

7:: ✅user dashboard>following module, 30min
    -- ✅render following talents.
    -- ✅unfollow talents
    -- try video thumbnail https://codepen.io/aertmann/pen/mrVaPx

✅Hey, how is it going. This is the Guide for talents (Not for users - I will send you the one for users later) For the languages, we will replace Spanish by Portugues. The guide for talents that I have sent is in both ENGLISH and FRANCAIS. Thanks


Next meeting: 16/Fab/2023 at 11:00PM
-----------------------------------------
Meeting: 004;

1:: ✅mail config
2:: stripe implement
3:: ✅user follower list

Next meeting: 17/Fab/2023 at 11:00PM
-----------------------------------------
Meeting: 005;

4::✅ on admin->pages add type from option field. <CSelect /> (
    -- value --
    privacy-policy
    terms-of-service
    -- name --
    Privacy policy
    Terms of service
    [{
        key: 'privacy-policy',
        value: 'Privacy policy'
    },
    {
        key: 'terms-of-service',
        value: 'Terms of service'
    }]
)

5::✅ Add spanish language

6::✅ if user->following list empty show no result found.

7:: ✅copy category module and make it Occasion module.

8:: ✅make wish module
    -- ✅table for save talent wish amount and status (minimum amount 30$).
    -- ✅if wish amount exist update amount else create.
    -- user wish for talent. (
        --user_id, talent_id, wish_amount, wish_details..., payment, transaction table.
    )

9:: ✅Tips
    -- ✅talent activate tips. (
        -- ✅minimum tips automatically set 10$.
        -- user can pay more.
    )

10:: ✅My life
    -- yearly subscription minimum 50$

11:: ✅user talent profile
    -- ✅tips, mylyfe etc show if active.

12:: ✅sub category
    ✅talent registration
        -- ✅category_id
        -- ✅sub_category_id
    ✅talent profile setup
        -- ✅category_id, sub_category_id



Next meeting: 19/Fab/2023 at 11:00PM
-----------------------------------------
Meeting: 006;

1:: ✅talent registration.. category, sub category filter
2:: ✅From talent profile -> redirect payment info page with service name.
    -- ✅save information on continue payment click save info to local storage
            and redirect to payment gateway page.
        -- ✅on continue payment click. show payment confirmation popup
            -- popup will contain total amount and service charge + commission
    -- ✅on gateway generate unique order number
            -- ✅make One  helper table for generate unique number
        -- ✅show service type

3:: talent/dashboard/payout
   ✅ bank information
        -- bank type
        input filds for stripe and bank.

Next meeting: 19/Fab/2023 at 11:00PM
-----------------------------------------
Meeting: 007;

1:: ✅on talent/dashboard/payout
    -- check if request amount is available.
2:: ✅on admin/dashboard/payments
    -- ✅show pending payment requests
    -- ✅By clicking pay  now button status update to paid
    --✅ by clicking cancel button soft delete

    -- ✅show pending and soft deleted list on payout processed tab


3:: ✅select talents for frontend
4:: ✅category parent could not be same parent.
5:: ✅category refector.
6:: ✅rename talent_earnings to talents_earnings_type
    -- ✅make talent_earnings for user payment
6:: ✅add balance field to user table

7:: on ad;min panel > wish request status column and done/button

    ✅talents_earnings
        talent_id, user_id, amount, commission_amount, commission, type, gender, instructio.............

        -- user can pay more. (look line)


----------------------------
✅select type on page create.

✅show talent on frontend after approve.

✅payment select occasion without

✅category which has talent

✅on left sidebar..
	✅sub category will dropdown under category

✅payment->card number will change on input


✅page type will unique
✅page type default value


Task by muhib:
---------------------
1. ✅Upload cover picture in talent "My life" menu
2. ✅Make functional/dynamic 'Add/Manage' under "My life" menu, after clicking preview button, show the cover 
    picture in banner section and show the video in bottom that talent uploaded in 'add/manage'
3. ✅Add our confirm modal in  T-Application's approved and decline button
4. ✅In frontend make contact page dynamic (controller, model, and migration created), 
    use php strip_tag function to avoid the html tag
5. ✅ In admin dashboard here is a menu called "Contact info", show the list of contact information 
    submitted from frontend, if admin click on replay button change the "seen" value to 1 in DB, and make reply function in modal.
6. ✅and we have a new page called "FAQ", we need to make this page from backend





✅home page/landing page: here please add the name of the person on the video. when we (talents/users who are logged in) click on a video it should redirect us to the talent profile page. 

✅if the person who clicks on it does not have an account or is not logged in then he should be redirected to the sign up/sign in page.

✅subcategories should be added under categories on ‘become a talent page’, ‘profile set up page’ for talent and in the admin/category page

✅=> we are missing categories page, faq page, and contact page on the bottom of the website pages we only see terms and privacy.

✅when a user create an account, they should get a verification email to activate their user account before having access to the website.

✅all checked box terms should be unchecked. user or talent will have to check them beofre proceeding and create an account or submitting the request to become a talent.

✅the red color on message and notification should only appear if a message or notification is actually received.

✅after become a talent request is sent, there should be a pop up to let the applicant know that their request has been submitted.


✅Show calendar section on user when there in talent profile if the talent has any calendar that has isSalable true/1

------------------------------------------
:: ✅admin settings add maintenance_charge field (percentage) / default 5%
:: ✅admin settings commission column, default value 20%

----------------------------------------------
:: ✅User can login if email is verified

:: ✅personalised video te popupe amount a service charge add hobe, commission na.
:: ✅cut commission from talent and cut service charge from user


✅contact page a akta dropdown add hobe, sobar upore, jar modde nicher option gulu thakbe:

✅1. General Inquiry
✅2. Refund status (users-only for wish requests not fulfilled on time)
✅3. Payout (talents only)
✅4. Talents related inquiry
✅5. Users related inquiry


http://127.0.0.1:8000/admin/dashboard/talent/111
----------------------------------------------
::admin dashboard -> talent -> talent details
    now dynamic

----------------------------
14-03-2023
admin should have their own login panel not same as website login


--------------------
Textarea component character limit issue.

once a talent is approved they should get an email nd let them know that was approve/decline – in case of approval they should get a link and when they click on the link it should first take tham to a page to set up their password and after that they should be redirected to the profile set up page to add a video, picture and fill all mandatory information.


-------------------------------------------
:: ✅check meta title and fave icon is missing?

:: ✅admin login page.

:: ✅Calender toaster after submit.

:: 

when send mail redirect to mail with sendbox parameter 

--------------------------
✅1.55: video size of video 3 is longer than the others (the one that you have deleted after)All videos should have the same size

2.55: When we click on the video to see it, it should expand and the size should be bigger.

3.08: Why does the menu look like this? It is too long. Please reduce the size as we discussed it previously

✅3.40: the video size at the bottom is ok but not the one at the top. All video should have the same size.

✅12.03: Calendar should not be on profile set up page but on talent profile. The profile set up page is where a talent add a video and picture that is.

Regarding the p.ay.out option please replace Bank by Pa.ypal that we discussed in the meeting. We will pa.yout talent using Stripe and Pa,ypal. Let me know if you have questions. Thank you

-----------------------

✅1:: Upload video thumbnail
2:: When click video show a popup carousel and show thumbnail and play button.
✅3:: Menu seed few category.
✅4:: All video should same size. (It's container size should same and show thumbnail as  preview)

✅5:: Profile video make public
    and only post video will be locked.

✅6:: profile setup page
    ✅-- remove calender. and add a video cover image.

✅7:: for user 
    ✅on talent profile page show video(cover) and calender that selected for frontend.

✅8:: talent payout section
        ✅remove bank and add paypal(amount, email, confirm email)


select issue


------------DOX--------------
✅1:: fix menu

✅2:: category show all page
✅3:: Occasion status type casts integer
✅4:: Notification button remove badge if notification is 0
    ✅-- notification icon show only for user
✅5:: by clicking logo back to home page.
✅6:: on talent account
    ✅-- first name and last name old value show
✅7:: on talent mylife
    ✅-- remove warning popup when talent change picture
✅8:: on admin panel
    ✅-- talent and user guide module (page module add calender, talent, user guid)
    ✅-- on page api, add condition to get only privacy policy and terms condition

    ✅on page.. add translation implementation

----------
✅meta title on talent profile page

meta title translate

------------------------------

:: ✅Toaster duration 2 sec
:: ✅search on click outside close (2 place)
:: ✅of canvas subcategory data not show

✅http://127.0.0.1:8000/wish/111
    pament details profile card
        remove email
            show category subcategory
            show back icon

::✅ Helper.getVideoDuration function by default 30sec 
    ✅(validate on post upload, and talent registration, and profile setup page)

-----------------
✅old password cannot new password
✅delete account text update
✅ redirect to setup page if cover image not found and not approved
✅ follow and subscription image dynamic

:: if user download a video. update expire date to previous date from now.
✅:: subscription issue
✅:: talent registration change

your task:
✅5.FIX OCCASION ALPHABETICAL ORDER (IN ADMIN AND WISH REQUEST)
✅6.FIX COUNTRIES ALPHABETICAL ORDER (IN ADMIN AND BECOME A TALENT)
✅7.BECOME A TALENT PROCESS
✅8.T-APPLICATIONS ADMIN
✅9.TALENTS (ADMIN)
✅11.SEARCH


--- NOT DONE YET ---
✅1- TIPS PAGE (MINIMUM AMOUNT IS STILL NOT WORKING) I CAN STILL PUT ANY NUMBER BELOW $10 AND PROCEED TO PA,YMENT
✅2- PASSWORD CHANGE FOR USERS IS STILL NOT WORKING
✅3- THE COUNTRIES ARE NOT IN ALPHABETICAL ORDER IN 'BECOME A TALENT FORM'
✅4- SEARCH ON HOME PAGE IS STILL NOT WORKING WELL, EVEN WHEN WE CLICK TO EXIT ONE LETTER STAYS IN THE SEARCH BAR


-------------after 15-Apr-2023---------------
✅::WHEN I CHANGE CATEGORIES ON TALENT PROFILE SET UP PAGE IT DOES NOT SAVE AND THIS PAGE OPENS.
✅::T-application verification status cast issue
✅::Admin Talents module Verification column add button instead of verified
    ✅--and when click this button show video.
✅::On Talent Profile setup page Add/Manage page crash issue.

<!-- ::Talent register video max size 50MB limit -->
<!-- and profile setup page video max size 50MB -->
    ✅and auto upload video when select.

:: on video 11 minute video size confusion.

✅:: scrollbar width set to 10 px
✅:: on talent analytics add today on filter dropdown
✅:: on talent payout add email on Payment Info and add paid date or N/A and status pending/paid/declined
✅:: Settings basic config disable field


✅1::Emails
        ✅1. When a user sets up an account (VERIFY EMAIL)

✅5. DASHBOARD ADMIN
✅6. ANALYTICS ADMIN
        Design and dynamic ready (working on calculation)
✅7. PAYMENT ADMIN (Sorting, and search multiple column ongoing)
✅8. LANGUAGES
✅9.SETTINGS






Revenue - 100
Net - 75
Service fees - 5
Ar pending hocche wish request pending


**---- new task ----**
✅--: stripe currency set to € euro.
--: add country and postal code field to payment method.
✅--: on payout request replace stripe to payonieer with logo.
✅--: under logo place text "Please add - Fees may apply (It should be small)"

✅--: tips amount min 10 and max 500
✅--: tips payment check.

✅--: wish request min 30 and max 2500
✅--: and also wish request setup page on talent panel.

✅--: talent and user on admin panel
    ✅-- rename delete to blocked.
    ✅-- add force delete button.
    ✅-- and add block icon to soft delete button
✅--: after contact submit send a copy to support@nextwisher.com


===========================

--: user delete with deleted true.
    and handle block when user login.

Please delete the following emails from the system (They are both talents)
habibdiakite@hotmail.com
hdnextw@gmail.com




$2y$10$s1jtH3v2gsgPaRKLaozN4uzX4NduqzS3w6QcGQCHmypEVHHnM0p8m


pawapay token

eyJraWQiOiIxIiwiYWxnIjoiSFMyNTYifQ.eyJqdGkiOiIzYTBmNjJlZi1kZmI2LTQzMDgtODFiZi04ODlhZmJiNzRmZTAiLCJzdWIiOiIyODkiLCJpYXQiOjE2OTY4NzAxMzgsImV4cCI6MjAxMjQ4OTMzOCwicG0iOiJEQUYsUEFGIiwidHQiOiJBQVQifQ.9Og8fZYj8FGxzvA-Dq0O2nYquXvYefqomv0ZstJOtQ0