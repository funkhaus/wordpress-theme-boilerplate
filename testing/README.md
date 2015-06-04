# Funkhaus Testing Guide

This is document serves as a checklist of bugs and coding issues we commonly see, along with guidance on how to fix those issues. It should be treated as a checklist before work is presented to Funkhaus. If a site can pass all these tests, then it is ready. Our desire is to push testing down to the contractors where possible. We stole this concept from [Kelly Johnson](http://www.lockheedmartin.com/us/aeronautics/skunkworks/14rules.html).

## Table of Contents
1. **Front End**
   - [Video Thumbtrays Overlapping Content](#video-thumbtrays-overlapping-content)
   - [Video Minimum Height](#video-minimum-height)
   - [Hotspots](#hotspots)   
   - [Menu Order Not Respected](#menu-order-not-respected)
   - [Footers Not Sticky](#footers-not-sticky)

___

Notes from Dave (that need to be checked and formatted):

**Global**
- We need to make sure have “-webkit-font-smoothing: antialiased; “ implemented site wide.
- Style the Backend of the Administration System with the graphics supplied in the “Production Kit / Backend Graphics / pngs”
- Implement Apple touch icon & Favicons
- Check all .svgs to make sure none of the edges are getting cut off due to uneven / fractional pixel dimensions.
- Google Tracking - Install the plugin and enter the UA #

**Image Compression on Server**
- Make sure we are compressing the images and not using exactly what the client uploaded.(as the client will inevitably upload a a huge file size)

**Type Licensing**
- We need to make sure we are using the font the client purchased.

**Safari Sweep**
Check the overall glitchyness that we always see at this phase of the beta.  A lot of the headline text is locking in the upper right hand corner, etc.

**Hamburger Menus**
- When the menu is opened, make all the rest of the site inactive. Like kill all slideshows, hover states, etc on the page on the right.
- Need to link up the social icons to the correct destination.
- Make sure the 3 bars do some sort of animation to and X 

**Lock to Top Navigations**
- Logo Mark @ Top needs to adopt the highlight color
- When the user stops scrolling down a page, fade off lock to top navigation. Then when the user starts scrolling again, show the navigation. Like http://www.anonymouscontent.com

**Home Page Horizontal Slideshows**
- When the user clicks to play a video / kill the slideshow
- When the user clicks left / right arrows (and / or uses keyboard arrows) kill auto rotate
- Images need to move with different gravity than the type.
- Make sure keyboard arrows left & right progress the slideshow 

**Home Page Vertical Scrolling**
- Make sure keyboard arrows up & down progress the slideshow
- Images need to move with different gravity than the type.
- allow scrolling to trigger slideshows
- User needs to be able to take over the scroll.
- Once the user takes over scroll, kill the auto scroll.

**Vimeo Settings**
- Make sure they use the website settings (No Social / White Scrubber)

**Director Landing Pages**
- Make sure all hover states are like the designs
- We will need to mask the thumbnail images to the dimensions in the design (as the client will inevitably upload a weird dimension)
- Make sure the grid is responsive if using mosaic.

**Director Video Detail**
- Sequential playback - On the last slide (video), close out to the work slideshow.
- Video should scale down so that the credits or any other page elements never overlap the video 

**Video Detail Pages**
- When the video is done playing have the vimeo player close
- Kill the forward and back arrow keys while the video is playing

**Thumb Trays**
- We need the surrounding area around the <  > icons to be 50px wide and the height of the tray so it is easier to click.
- When hovering over a thumbnail it should illuminate to 100% opacity and the others should go to 25% opacity
- When hovering over a thumbnail, the credits for that thumbnail should be showing, on rollout, the credits should go back to the spot that is currently playing.
- The amount of thumbnails should be variable to the width of the page
- If there is no need to have pagination arrows on the tray, do not show them. On the first page, hide the left arrow. On the last page, hide the right arrow. If all the thumbnails fit on one page, show no arrows.
- When clicking the pagination of the thumbnail tray make the page slide, not just have one thumbnail slide over at a time. 
- The width of the browser determines the pages of the thumbnails in the thumbtray

**Contact Pages**
- Make sure all map links pop open in a new browser window
- Make sure that the all email address’s link to the correct email address
- Make sure all hover states are implemented
- Need to link up the social icons to the correct destination.

**News Details**
- Photo Galleries are installed and working
- Highlight states for all inline text links are working.

**Newsletter Forms**
- Newsletter form needs to have validation implemented.
- Hook up Newsletter Sign up form with Mail Chimp info provided by the client.
- Get an email address from the client where they would like the email(s) to be sent.
