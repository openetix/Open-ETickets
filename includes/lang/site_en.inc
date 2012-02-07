<?php
/*

  Please add new defines only at the bottom of the file. This makes it easier for translators to
  find new defines.

  Thanks

*/
// These defines are located at the beginning of this file and are used throughout Fusion Ticket.
// Use caution when changing and try not to be page specific with the definition.
// Date & Time Formats, Buttons & Links, Navigation, Passwords, Globals, and Warnings.
// These defines are next and mostly page specific.
// Admin Pages, Control Pages, Web Templates, and POS Templates
// SetLocate settings:
define('setlocale_ALL', 'en.utf8; en.utf-8;en_EN.utf8;en_EN.utf-8;en-EN.utf8;en-EN.utf-8;en;en_EN;en-EN');
// define('setlocale_TIME','en.utf8; en.utf-8;en_EN.utf8;en_EN.utf-8;en-EN.utf8;en-EN.utf-8;en;en_EN;en-EN');
// DATE & TIME FORMATS
// comment "//" out the format not used.
// Euro Day/Date
// define("date_format","%A %e %B %Y");
// US Day/Date
define("date_format", "%A - %B %e, %Y");
// 24 hour
// define("time_format","%H:%M");
// 12 hour
define("time_format", "%I:%M %p");

define("longdate_format", "%Y-%m-%d %H:%M:%S");
define("shortdate_format", "%e %b %Y");
define("shorterdate_format", "%h %e");
define("date", "Date");
define("dd_mm_yyyy", "(dd-mm-yyyy)");
define("yyyy_mm_dd", "(yyyy-mm-dd)");
define("date_from", "Date From");
define("date_to", "Date To");
define("dts_day_arr", "Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday");
// BUTTONS & LINKS
// for continuity all Buttons & Links should be defined the same if initiating the same action
define("add", "Add");
define("add_row", "Add");
define("add_venue", "Add ");
define("add_tickets", "Add Ticket(s)");
define("admin_list", "List");
define("cancel", "Cancel Order?");
define("checkout", "Check Out");
define("clear", "Clear");

define("close", "Close");
define("continue", "Continue");
define("define", "Define");
define("edit", "Edit");
define("delete", "Delete");
define("login_button", "Login");
define("order_it", "Order");
define("order_more_tickets", "Order More Tickets");
define("publish", "Publish");
define("pwd_send", "Send Password");
define("remove", "Remove");
define("res", "Reset");
define("reserve", "Reserve");
define("save", "Save");
define("save_payment", "Save &amp; Email payment note");
define("save_ship", "Save &amp; Email shipping note");
define("save_note", "Save &amp; Email a note");
define("search", "Search");
define("show_seats", "Seating Chart");
define("submit", "Submit");
define("unpublish", "Unpublish");
define("view", "View");
define("view_order", "View Order");
define("icon_unpublish", "Click to UnPublish");
define("icon_unpublish_alt", "Published");
define("icon_publish", "Click to Publish");
define("icon_publish_alt", "New Event");
define("icon_nosal", "Click to RePublish");
define("icon_nosal_alt", "UnPublished");
// TOOLTIPS COULD BE MOVED TO ANOTHER FILE
// By default they use the name of the item plus _tooltip eg. add_venue button would be add_venue_tooltip
define("add_tooltip", "Add");
define("add_multi_tooltip", "Add sub-event to this main event.");
define("edit_tooltip", "Edit Item");
define("add_venue_tooltip", "Add a new Venue");
define("compile_all_tooltip", "This will (re)compile all the templates to check they are valid.");
define('copy', 'Copy');
define('add_pm', 'Add Placemap');
define('pm_split', 'Split Placemap');
define('split_zones', 'Split Zones');
define('Published', 'Published');
define('place_map_tooltip', 'Edit Event Seating and Discounts');
define('Archive', 'Archive');
define('details', 'Details');
define('print', 'Print');
define('set_status_ord', 'Set to Ordered');
define('set_status_shipment_send', 'Set to Sent');
define('set_status_shipment_none', 'Set to Not Sent');
define('set_status_payment_paid', 'Set to Paid');
define('set_status_payment_none', 'Set to Not Paid');
define("save_payment_tooltip", "Save &amp; Email the above note to the customer.");
define("save_ship_tooltip", "Save &amp; Email shipping note");
define("save_note_tooltip", "Save &amp; Email a note");
define('delete_ticket', 'Delete Ticket');
// NAVIGATION
// should remain the same throughout all pages.
define("nav_first", "First");
define("nav_prev", " < ");
define("nav_next", " > ");
define("nav_last", "Last");
// PASSWORDS
define("password", "Password");
define("password1", "Choose Password");
define("password2", "Repeat Password");
define("new_password", "New Password");
define("old_password", "Current Password");
define("confirmpassword", "Confirm Password");
// Globals
// basic words used throughout the program
define("event", "Event");
define("home", "Home");
define("logout", "Log out");
define("member", "Member");
define("no", " No");
define("order_id", "Order Number");
define("remove_image", "Remove Image");
define("tickets", "Tickets");
define("welcome", "Welcome");
define("yes", " Yes");
// User Globals
// basic user information not page specific.
define("user_firstname", "First Name");
define("user_lastname", "Last Name");
define("user_name", "Name");
define("user_address", "Address");
define("user_address1", "Address 1");
define("user_address2", "Address 2");
define("user_city", "City");
define("user_state", "State");
define("user_state_name", "State");
define("user_zip", "Zipcode");
define("user_country", "Country");
define("user_country_name", "Country");
define("user_phone", "Phone");
define("user_fax", "Fax");
define("user_email", "E-Mail");
define("user_id", "User ID");
define("user_status", "Status");
// Warnings and Flags
// many of these are page specific but are more easily controlled in one spot.
define("act_err", "User Not Found or Already Activated!");
define("act_error", "Account Could Not be Activated! Please re-check the link or contact the system administrator.");
define("act_uselink", "Please use the link that has been sent to your email address.");
define("act_email_sent", "Activation email sent.");
define("already_exist", "This Name Already Exists. Choose Another!");
define("useralreadyexist", "This E-mail is Already Registered in the Database!");
define("AutoCancel_order", "Auto Cancel: Failed to Order!");
define("AutoCancel_paying", "Auto Cancel: Failed to Pay for Order!");
define("cant_delete_self", "You can not delete yourself!");
define("cannot_find_seat", "Cannot Find Seat!");
define("cannot_seal", "Internal Error: Cannot Seal Credit Card Info!");
define("cannot_store", "Internal Error: Cannot Store Credit Card Info!");
define("check_condition", "Please Accept the Terms!");
define("compilation_succeed", "Success - Template Compiled!");
define("correct_errors_first", "Correct Errors First!");
define("delete_confirm_msg", "Delete This Event?");
define("delete_item", "Delete?");
define("deleted_all_tickets", "Deleted All Tickets!");

define("delete_subs_first", "Delete Sub-Events First!");
define("email_valid", "Please Enter a Valid Email Address!");
define("email_match", "The Emails Do Not Match!");
define("email_badconfig", "The program's mail configuration is incorrectly defined!");

define("event_order_limit_exceeded", "Sorry, The number of seats that you can order for this event is limited!");
define("fax_short", "The Fax Number is too Short!");
define("fax_long", "The Fax Number is too Long!");
define("img_loading_problem", "Problem Loading Image!");
define("img_loading_problem_copy", "Problem Loading Image Copy!");
define("in_use", "This Object is in use and <b>cannot</b> be Deleted!");
define("internal_error", "Internal Error!");
define("invalid_date", "Invalid Date!");
define("invalid_number", "Invalid Credit Card Number!");
define("log_err_isactive", "That account is already active.");
define("log_err_mailnotsend", "The Activation Email <b>could not</b> be sent, please check you have entered the correct email address.");
define("log_err_not_act", "You are Not Activated!");
define("log_err_not_act_info", "Your account is not yet activated.
	<p>Please check your email to activate your account. Don't forget to check the spam folder.</p>
	<p>If you haven't received any email yet, please click <a href='resend_activation.php'>here</a> to resend the activation email.</p>");
define("log_err_wrong_usr", "Wrong Username or Password!");
define("log_err_wrong_usr_info", "Wrong Username or Password.
		If you have forgoten your password, click <a href='forgot_password.php' onclick='showDialog(this);return false;' >here</a>");
define("mand_all", "All input fields are Required.");
define("mandatory", "Field Required!");
define("MP3_loading_problem", "Problem Loading MP3 File!");
define('missing_pm_ord_id', 'Error: this placemap cannot be saved, venue is missing');
define("nosale_confirm_msg", "Remove from Sale?");
define("new_order_created", "New Order Created");
define("not_number", "Enter a Number Without -()!");
define("not_valid_email", "E-mail is invalid.");
define("no_result", "No Result!");
define("old_order_cancelled", "Old Order Cancelled!");
define("order_allready_cancelled", "Order Already Cancelled!");
define("order_cancelled", "Order Cancelled!");
define("order_cannot_reissue", "Can Not Re-Issued!");
define('order_error_message', 'Order Error Message');
define('order_madefinal', 'Order is Complete');
define("order_not_found", "Order Not Found!");
define('OrderNotFound', 'Order Not Found');
define("order_not_cancelled", "Order Not Cancelled!");
define('order_notice_message', 'Order Notice Message');
define('order_paid_already', 'Order Already Paid');
define('order_paid_total', 'Total Paid');
define("pass_not_egal", "Passwords Do Not Match!");
define("pass_match", "Passwords do not match");
define("pass_too_short", "Password is too short");
define("phone_long", "The Phone Number is too long");
define("phone_short", "The Phone Number is too short");
define("places_empty", "Please select your seats");
define("places_occupied", "The selected seats have been already reserved");
define("places_remains", " Remaining seats");
define("places_toomuch", "This Number of Seats cannot be Reserved!");
define("pwd_not_thesame", "The Passwords Entered Do Not Match!");
define("pwd_second_missing", "Second Password is Missing!");
define("pwd_to_short", "Password Too Short!");
define("pub_confirm_msg", "Publish Event?");
define("reservate_failed", "Internal Error: Impossible to Save Seats ");
define("restart_success", "Successfully Restarted!");
define("save_successful", "Changes Saved");
define("save_failed", "Internal Error: Impossible to Save the Order ");
define("send_order_email", "Send Order by E-mail and Change Status to 'Sent'");
define("split_pm", "Split Seat Chart");
define("Status_is_pub", "This Event is Published!");
define("stop_success", "Successfully Stopped!");
define("system_halted", "<b>SYSTEM IS HALTED!<b>");
define('This_page_is_not_secure', 'Attention: Your Registration and Order Details are not encrypted. However payments using Paypal will use secure pages (SSL).');
define("ticket_deleted", "Ticket Deleted!");
define("ticket_not_found", "Ticket Not Found in Database!");
define("tickets_printing", "Tickets are Printing. Please Wait!");
define("too_low", "Please Enter a Number Larger than 0 !");
define("To_Trash", "Sent to Garbage");
define("undefined", "Undefined!");

define('err_blank_or_allready', 'Error, Blank or already in use!');
define('compilation_failed', 'Compilation Failed!');
define('ClassCatch:', 'Class Catch:');
define('wrong_input_ids', 'Wrong Input Id');
define('file_not_exsist', 'File does Not Exist!');
define('username_not_found', 'User not registered. Please provide a valid email.');
define('search_choice_one_field', 'Choose a Field to Search!');
define('search_choice_event', 'Choose an Event to Search!');
define('search_choice_seat_row_nr', 'Choose a Seat/Row to Search!');
define('Options_saved', 'Options Saved!');
define('update_error', 'Update Error!');
define('user_details_saved_successfully', 'User details successfully updated');
// ------------------------------------------------------
// ADMIN
// these defines are seperated by page and are page specific.
// indents indicate the define is a sub-page or a drop down list.
// Administration Menu - the menu on the left side
define("index_admin", "Home");
define("administration", "Administration");
define("tabs-admins_admin", "User Management");
define("tabs-events_admin", "Event Management");
define("statistic_admin", "Reporting");
define("orders_admin", "Ticket Tracking");
define("template_admin", "Templates");
define("handlings_admin", "Order Handling");
define("search_admin", "Search");
define("transports_admin", "Import /Export");
define("utilities_admin", "Utilities");
// Home
// System Information
define("index_admin_tab", "Information");
define("current_version", "Version:");
define("InfoWebVersion", "Server Software");
define("InfoPhpVersion", "PHP Version");
define("InfoMysqlVersion", "MySQL Version");
define('InfoMysqlDB', 'Database');
define("InfoUserCount", "User count:");
define("InfoGroupCount", "Group count:");
define("InfoVenueCount", "Venue count:");
define("InfoEventCount", "Event count:");
define("InfoAdminCount", "Admin count:");
define("system_summary", "System Summary");
define("index_admins_count", "<b>Admins:</b> %d, <b>Managers:</b> %d, <b>Pos:</b> %d, <b>TicketTakers:</b> %d");
define("index_user_count", "<b>POS:</b> %d, <b>Guests:</b> %d, <b>Members:</b> %d/%d/%d");
define("index_events_count", "<b>Pub:</b> %d, <b>New:</b> %d, <b>Nosale:</b> %d, <b>Trash:</b> %d, <b>Total:</b> %d");
define("index_ort_count", "%d");
// Merchant
define("owner_tab", "Merchant Details");
define("merchant_update_title", "Modify Merchant Information");
define("organizer_name", "Name");
define("organizer_address", "Address");
define("organizer_plz", "Zip");
define("organizer_ort", "City");
define("organizer_state", "State");
define("organizer_country", "Country");
define("organizer_phone", "Phone");
define("organizer_fax", "Fax");
define("organizer_email", "E-Mail");
define("organizer_currency", "Currency");
define("organizer_logo", "Merchant's Logo");
define("organizer_remove_image", "Remove Logo");
// Configuration
define("shopconfig_tab", "Configuration");
define("option_update_title", "Config Shop Auto-Cleanup settings.");
define("shopconfig_lastrun_int", "Cleanup check interval (0 = off)");
define("shopconfig_restime", "Reservation time (0 = off)");
// define("shopconfig_restime_remind","When reminder email is sent");
define("shopconfig_check_pos", "Check for Unpaid POS Orders?");
define("confirm_yes", "Yes");
define("confirm_no", "No");
define("shopconfig_delunpaid", "Global Delete Unpaid Orders?");
define("shopconfig_delunpaid_pos", "Delete UnPaid POS Orders?");
define("option_others_title", "Other Options");
define("shopconfig_user_activate", "Users Checkout Level");
define ("act_restrict_cart", "Restrict Cart to Members");
define("act_restrict_all", "Only activated members");
define("act_restrict_later", "New members are allowed");
define("act_restrict_w_guest", "New members and guests");
define("act_restrict_quest_only", "Only guest users");
define("shopconfig_maxres", "Max tickets to reserve");
define("shopconfig_posttocollect", "Switch to Alt Payment");
define("res_delay", "Shopping Cart Timeout");
define("cart_delay", "Database Cart Timeout");
define("res_delay_less_cart", " < Can not be less than Cart Timeout");
// System Managers
define("admin_login", "Login Name"); //used in admin, organizer, and spoint

define("admin_user_tab", "Admins");
define("admin_id", "Admin ID");
define("admin_inuse", "Enabled?");
define("admin_ismanager", "Is manager");
define("admin_user_title", "System Administrators");
define("admin_update_title", "Modify Administrator");
define("admin_add_title", "Add System Administrator");
// Sale Points
define("spoint_tab", "Points of Sale");
define("pos_user_title", "Points of Sale");
define("pos_add_title", "Add a Point of Sale");
define("pos_update_title", "Modify Point of Sale");
define("kasse_name", "POS Name");
define("user_prefs_print", "Preferences");
define("user_prefs_print_pdf", "Open Orders in Acrobat");
define("user_prefs_print_pdt", "Send Orders to Printer");
define("user_prefs_strict", "Use Office On fixed PC");
define("user_prefs_store_now", "Store cookie now");
define("user_nickname", "Nickname");
define("print_warning", "When you want to directly print the PDF's, you need to setup our system.");
// Ticket Takers
define("control_tab", "Ticket Takers");
define("control_user_title", "Ticket Takers");
define("control_view", "Ticket Taker");
define("control_add_title", "Add Ticket Taker");
define("control_update_title", "Modify Ticket Taker");
define("control_login", "Login Name");
define("control_event_ids", "Authorised Events");
// Event Section
define("event_status", "Event Status");
define("reset_to_main", "Tick_to_reset_to_main_event.");
// Venues
define("ort_admin_tab", "Venues");
define("ort_admin", "Venues");
define("ort_title", "Venues");
define("ort_add_title", "Add Venue");
define("ort_insert_title", "Add Venue");
define("ort_update_title", "Modify Venue");
define("ort_id", "Venue ID");
define("ort_name", "Venue Name");
define("ort_address", "Address");
define("ort_address1", "Address 1");
define("ort_zip", "Zip");
define("ort_city", "City");
define("ort_state", "State");
define("ort_country", "Country");
define("ort_phone", "Phone");
define("ort_fax", "Fax");
define("ort_url", "Homepage");
define("ort_pm", "Googlemaps link");
define("ort_image", "Venue Logo");

define("place_maps", "Seat Charts");
define("edit_pm", "Modify Seat Chart");
define("pm_id", "Seat Chart ID");
define("pm_event_id", "Event ID");
define("pm_name", "Seat Chart Name");
define("pm_image", "Seat Chart Image");

define("categories", "Categories");
define("both", "Rows/Seats");
define("rows", "Rows");
define("seat", "Seats");
define("none", "None");
define("cat_at", "@");
define("category_id", "Category Number");
define("category_name", "Category Name");
define("category_price", "Category Price");
define("category_template", "Template for Tickets");
define("category_color", "Color for Category");
define("category_numbering", "Category Numbering");
define("category_numbering_both", "Rows and Seats Numbered");
define("category_numbering_rows", "Only Rows Numbered");
define("category_numbering_seat", "Only Seats Numbered (No Rows)");
define("category_numbering_none", "Seats w/o Numbers (Open Seating)");

define("category_size", "Category Size");
define("number_taken", "Seats Sold");
define("category_data", "Category Data");
define("category_new_size_title", "Change Category Size");
define("category_new_size", "New Size");

define("pm_zones", "Zones");
define("pm_zone", "Zone");
define("pmz_pm_id", "Seat Chart ID #");
define("pmz_color", "Zone Colour");
define("pmz_name", "Zone Name");
define("pmz_short_name", "Zone Nickname");
define("pmz_id", "Zone Record Number");
define("pmz_ident", "Zone Number");
define("place_map", "Seat Chart"); //link

define("pm_parts", "Seat Chart Parts");
define("pm_part", "Seat Chart Part");
define("pmp_id", "Seat Chart Part ID");
define("pmp_name", "Seat Chart Part Name");
define("pmp_width", "Seat Chart Part Width");
define("pmp_height", "Seat Chart Part Height");
define("pmp_scene", "Scene Location");
define("pmp_scene_none", "None");
define("pmp_scene_north", "North");
define("pmp_scene_east", "East");
define("pmp_scene_south", "South");
define("pmp_scene_west", "West");
define("pmp_shift", "Shift Rows");
// do not edit.  Places stage image
define("scene_h", "../images/scene_h_en.png");
define("scene_v", "../images/scene_v_en.png");

define("label", "Label:");
define("label_type_text", "Text");
define("label_type_row_east", "Row# on Right");
define("label_type_row_west", "Row# on Left");
define("label_type_seat_south", "Seat# Below");
define("label_type_seat_north", "Seat# Above");
define("label_type_exit", "Exit");

define("seat_numbering", "Seat Numbering");
define("autonumber_pmz", "Automatic numbering");
define("first_row", "First Row");
define("step_row", "Row Step");
define("inv_row", "Invert Rows");
define("first_seat", "First Seat");
define("step_seat", "Seat Step");
define("inv_seat", "Invert Seat");
define("flip", "Flip");
define("keep", "Keep");
define("map", "Chart"); //link

define("ort", "Venue");
define("ort_plan_nr", "Number on plan");
// Event Groups
define("event_group_tab", "Groups");
define("event_group_title", "Event Groups");
define("event_group_add_title", "Add an Event Group");
define("event_group_update_title", "Modify Event Group");
define("event_group_id", "Group ID");
define("event_group_name", "Group Name");
define("event_group_start_date", "Start Date");
define("event_group_end_date", "End Date");
define("event_group_type", "Group Type");
define("festival", "Festival");
define("tournee", "Tournament");
define("theatre", "Theatre");
define("event_group_description", "Description");
define("event_group_image", "Event Group Image");

define("unpublish_event_group", "Unpublish Event Group");
define("publish_event_group", "Publish Event Group");
// Events
define("event_tab", "Events");
define("history_tab", "History");
define("event_history_title", "Previous Events");
define("event_title", "Events");
define("checkbox_all", "Check/Uncheck All");
define("also_sub", "Sub-events also");
define("event_add_title", "Add a New Event");
define("event_edit_title", "Modify Event");
define("event_update_title", "Modify Event");
define("add_multi", "Add Sub-Event");

define("event_id", "Event ID#");
define("event_name", "Event Name");
define("event_pm_ort_id", "Seat Chart");
define("agenda_only", "Agenda Only");
define("event_ort_name", "Venue Name");
define("event_rep", "Repeatability");
define("event_rep_main", "Multiple");
define("event_rep_unique", "Unique");
define("main,sub", "Unique");
define("event_short_text", "Event Short Text");
define("event_text", "Event Long Text");
define("event_url", "Homepage");
define("event_date", "Date");
define("event_recur_type", "Re-Occurs");
define("recure_nothing", "No");
define("recure_daily", "Daily");
define("event_recur_end", "Event Stops");
define("recure_days_selection", "Days to Exclude");
define("event_time", "Event Begins");
define("event_open", "Event Open");
define("event_end", "Event Ends");
define("event_order_limit", "Order Limit");
define("event_template", "Template for Tickets");
define("event_type", "Event Type");
define("ballet", "Ballet");
define("blues", "Blues");
define("sacred", "Church music");
define("cinema", "Cinema");
define("classics", "Classical music");
define("exposition", "Exposition");
define("folklore", "Folklore");
define("funk", "Funk");
define("humour", "Humour");
define("jazz", "Jazz");
define("music", "Music");
define("opera", "Opera");
define("other", "Other");
define("party", "Party");
define("pop", "Pop");
define("rock", "Rock");
define("theater", "Theatre");
define("event_image", "Event Image");
define("event_mp3", "MP3");
define("undefined_category", "Category undefined");
// Discounts
define("discount_title", "Discounts");
define("discount_add_title", "Add a Discount");
define("discount_category_id", "Event Category");
define('discount_cond', 'Discount Conditions');
define("discount_update_title", "Modify Discount");
define("discount_id", "Discount ID");
define("discount_name", "Discount Name");
define("discount_type", "Discount Type");
define("discount_type_fixe", "Fixed Discount");
define("discount_type_percent", "Percent Discount");
define("discount_value", "Discount Value");
// Statistics
define("event_stats_title", "Statistics");
define("show_text_stats", "Text");
define("show_grafik_stats", "Graph");
// Order Overview
define("orders_handlings_tab", "Handling");
define("orders_event_tab", "Events");
define("order_list_title", "Orders by Payment Handling");
define("order_event_list_title", "Orders by Event");
define("order_type_ordered", "Ordered");
define("order_type_pending", "Pending");
define("order_type_cancelled", "Cancelled");
define("order_type_reissued", "Re-issued");
define("order_type_paid", "Paid");
define("order_type_sended", "Sent");
define("order_type_reserved", "Reserved");
define("purge", "Purge");
define("order_patron", "Customer");
define("order_place", "Created By");
// View Order
define("order_nr", "Order #: ");
define("order_tickets_nr", "Number of Tickets");
define("tickets_nr", "Quantity");
define("order_fee", "Fee");
define("order_total_price", "Total Price");
define("order_date", "Order Date");
define("order_shipment_status", "Shipment Status");
define("order_place", "Order created by");

define("send", "Sent");
define("order_payment_status", "Payment Status");
define("order_paid_total_view", "Order Total");
define("order_payment_id", "Payment ID");
define("order_status", "Status");
define("processed", "Processed");
define("order_deleted_manual", "Order Manually cancelled");
define("order_responce", "Cancellation Type");
define("order_responce_date", "Cancellation Date");
define("order_delete_now", "Are you sure to remove this order?");
define("order_details_tickettab", 'Tickets');
define("order_details_usertab", 'User');
define("order_details_logtab", 'Status log');
define("order_details_notetab", 'Note');
define("order_statuslog", 'Order status log');
define("order_note", 'Don\'t forget to save the note.');
define("order_note_title", "Note");
define("order_add_note", "Add Note");
define("onote_type", "Note Type");
define("on_type_note", "Generic Note");
define("on_type_admin", "Admin Note");
define("on_type_action", "Action/ToDo");
define("on_type_payment", "Payment Details Note");
define("on_type_ship", "Shipping Details Note");
define("on_type_todo", "ToDo");
define("note", "Note");
define("admin", "Administrator");
define("ship", "Shipping");
define("pos", "POS User");
define("posman", "POS Manager");
define("organizer", "Event Manager");
// Action icons
define("change_status_to_ord", "Change Status to Ordered?");
define("change_status_to_send", "Change Status to Sent?");
define("change_status_to_no_send", "Change Status to Not Sent?");
define("change_status_to_paid", "Change Status to Paid?");
define("change_status_to_no_paid", "Change Status to Not Paid?");
define("reissue_order", "Update this order and re-issue with new tickets?");
define("reissue_ticket", "Re-issue the ticket (recalculate unique barcode)? Ticket Nr:");
// Legend Menu
define("legende", "Explanation");
define("reserved", "Reserved");
define("ordered", "Ordered");
define("pending", "Pending");
define("paid", "Paid");
define("sended", "Sent");
define("paid_and_send", "Paid and Sent");
define("cancelled", "Cancelled");
define("reissue", "Re-issued");
// Action menu
define("possible_actions", "Possible Actions");
define("view_order_details", "View Details");
define("print_order", "Print Order");
define("change_order_to_ord", "Mark 'Ordered'");
define("send_order_post", "Mark 'Sent'?");
define("no_send_order_post", "Mark 'Not Sent'");
define("change_order_to_paid", "Mark 'Paid'");
define("change_order_to_send", "Mark 'Sent'");
define("change_order_to_no_paid", "Mark 'Not Paid'");
define("reissue_order_menu", "Update this Order and Issue with new tickets.");
define("cancel_order", "Mark `cancelled` and Delete Tickets");
// Templates
define("templ_System", "System Emails");
define("templ_email", "Emails");
define("templ_swift", "Simple Email");
define("templ_pdf2", "Html2pdf's");
define("templ_pdf", "Old Pdf's");
define("template_title", "Templates");
define("template_add_title", "Add a Template");
define("template_update_title", "Modify Template");
define("lang", "Language");
define("lang_en", "English");
define("email_from", "From:");
define("email_to", "To:");
define("email_subject", "Subject");
define("email_text", "Text Section");
define("email_html", "HTML Section");
define("template_id", "ID #");
define("template_type", "Type");
define("template_name", "Name");
define("compile_all", "Compile all");
define("email_to_name", "Email to name:");
define("email_to_email", "To email address:");
define("email_from_name", "Email from name:");
define("email_from_email", "From email address:");
define("emails_cc", "CC emails:");
define("emails_bcc", "BCC emails:");
define("email_def_lang", "Default template language:");
define("email_templates", "Email templates:");
define("remove_group", "Remove");
define("template_subject", "Template language subject");
define("template_text", "Template language plain text");
define("template_html", "Template language HTML");
define("send_test", "Send Test Email");
define('email_header', 'Email Header');
define('email_body', 'Email Body');
// Order Handling
define("fees", "Fee's");
define("handling_title", "Order Handling");
define("handling_add_title", "Add an Order Handling");
define("payment_update_title", "Modify the Payment Type");
define("handling_update_title", "Modify the Payment Type");

define("handling_payment", "Payment");

define("cash", "Cash");
define("entrance", "At the Entrance");
define("invoice", "Invoice");
define("paypal", "PayPal");
define("ideal", "iDEAL");
define("Google", "Google");
define("authorize_aim", "AIM");
define("ebs", "EBS");
define("email", "Email");

define("post", "Post");
define("google", "Google");
define("handling_shipment", "Shipment");
define("handling_sale_mode", "Selling Method");
define("www", "Webshop");
define("sp", "POS");
define("handling_alt", "Alt Handling Method");
define("handling_no_alt", "Hide");
define("handling_alt_only", "Only Alt Handling Method");
define("handling_expires_min", "Minutes until OH Cancels for<br> Non-Payment (0 = Never)");
define("handling_fee_fix", "Fee Fixed");
define("handling_fee_percent", "Fee Percent");
define("handling_email_template_ord", "Email to send when Ordered");
define("handling_email_template_send", "Email to send when Shipped");
define("handling_email_template_paid", "Email to send when Paid");
define("handling_pdf_template", "PDF Template for Invoice/Receipt");
define("handling_pdf_ticket_template", "PDF Ticket Template");
define("handling_text_payment", "Payment Label");
define("handling_text_shipment", "Shipment Label");
define("handling_html_template", "HTML Template for Explanations");
define("handling_email_template_ord_incl_pdf", "Include PDF's for Order");
define("handling_email_template_paid_incl_pdf", "Include PDF's for Paid");
define("handling_email_template_send_incl_pdf", "Include PDF's for Sent");
define("handling_email_template_ord_incl_inv_pdf", "Invoice/Receipt");
define("handling_email_template_paid_incl_inv_pdf", "Invoice/Receipt");
define("handling_email_template_send_incl_inv_pdf", "Invoice/Receipt");
define("handling_email_template_ord_incl_ticket_pdf", " Ticket");
define("handling_email_template_paid_incl_ticket_pdf", " Ticket");
define("handling_email_template_send_incl_ticket_pdf", " Ticket");
define("handling_only_manual_send", "Always manually send email");
// paypal
define("pm_paypal_business", "PayPal Account");
define("pm_paypal_test", "PayPal Sandbox");
// ideal
define("pm_ideal_merchantid", "Merchant ID");
define("pm_ideal_subid", "Sub ID");
define("pm_ideal_issuer", "Issuing Bank");
define("pm_ideal_test", "Ideal Sandbox");
define("pm_ideal_key", "Key");
define("pm_ideal_keypass", "Key Password");
define("pm_ideal_pcert", "Pcert");
define("pm_ideal_certficate", "Certificate");
// Google
define("pm_google_merchant_id", "Merchant ID");
define("pm_google_merchant_key", "Key");
define("pm_google_sandbox", "Sandbox");
define("pm_google_callback_link", "Call Back Link");
define("pm_on_google_cancel_cancel_order", "Google Cancellation");
define("pm_on_user_cancel_cancel_order", "User Cancellation");
define("pm_on_refund_cancel_order", "Refund");
// ebs
define("pm_ebs_accountid", "Account ID");
define("pm_ebs_seckey", "Security Key");
define("pm_ebs_test", "EBS Test");
// aim
define("pm_authorize_aim_login", "AIM Login");
define("pm_authorize_aim_txnkey", "Transaction Key");
define("pm_authorize_aim_test", "AIM Test");
// Search
define("patron_tab", "Customer");
define("search_title_user", "Search for Customer");
define("sale_point", "POS");
define("guest", "Guest");

define("search_result", "Search Result");
define("seat_tab", "Seat");
define('seat_status','Status');
define('seat_code','Barcode');
define("search_title_place", "Search Seats");
define("event_list", "Event List");
define("seat_row_nr", "Row Number");
define("seat_nr", "Seat Number");
define("choice_please", "Choose Please...");
define("category", "Category");
define("place", "Seat");
define("price", "Price");
define("bs", "Ord.#");
define("status", "Status");
define("order_tab", "Order");
define("search_title_order", "Search Orders");define("barcode_tab", "Barcode");
define("search_title_codebar", "Search Barcodes");
define("codebar", "Barcode");
// Import/Export
// Export
define("export_admin_title", "Export Data");
define("export_xml_event_title", "Export Data");
define("export_xml_event_submit", "Export It!");
define("export_xml_event_file", "Filename");
define("export_xml_calendar", "Calendar");
define("export_xml_calendar_event", "Calendar");
define("export_templates", "Templates");
define("export_template_id", "Choose Template ");
define("export_template_file", "Filename");
define("export_submit", "Export It!");
define("export_xml_event", "Event in XML");
define("export_xml_event_event", "Choose an Event");
define("export_xl", "Sales Data");
define("xl_view_title", "Sales Data");
define("xl_start", "Start Date");
define("xl_end", "End Date");
define("generate_xl", "Generate Sales Worksheet");
define("seat_id", "Ticket Number");
define("seat_price", "Seat Price");

define("export_cc", "Credit Cards");
define("export_cc_title", "Export Credit Card Numbers");
define("export_cc_count", "Credit Card Count");
define("export_entrant", "Export Attendees");
define("export_entrant_title", "Ticket Status Report");
define("export_entrant_event", "Choose an Event");
define("export_entrant_NotSended", "\'Not Sent\' Orders only");
define("export_entrant_order_id", "Order ID");
define("export_entrant_fullname", "FullName");
define("export_entrant_city", "City");
define("export_entrant_tickets", "Tickets");
define("export_entrant_price", "Total Due");
define("export_entrant_totalprice", "Totals");
define("export_entrant_seats", "Seats");
define("time", "Time");
// Import
define("import_admin_title", "Import Data");
define("import_xml_title", "Import Files");
define("import_xml_submit", "Import It!");
define("import_xml", "Event in XML");
define("import_xml_file", "Import File");
define("import_template", "Templates");
// define("import_template_name","Name"); see templates
define("import_langauge", "Language Files");
define("import_csv_file", "Import File Name");
define("langauge", "New Language File Name");
// Utilities
// Orphans
define('orphan_tab', 'Orphans');
define("Record_Orphan_Test", "Orphaned Database Records");
// Garbage
define('garbage_tab', 'Garbage');
define('garbage', 'Garbage');
define("empty_trash", "Empty");
define("unused_guests", "Unused Guests");
define("order", "Orders");
// Email Log
define('emaillog_tab', 'Email Log');
define('email_log_list_title', 'Email List');
define('el_timestamp', 'Date Sent');
define('el_action', 'Action');
define('el_email_to', 'Addressed To:');
define('el_failed', 'Failed');
define('el_received', 'Received');
define('el_log', 'Return Log');
define('el_bad_emails', 'Bad Emails');
define('el_email_message', 'Email Message');
// Backup
define('backup_tab', 'Backup');
define('backup_title', 'Back Up Database');
define('comp_method', 'Compression Method');
define('comp_level', 'Compression Level');
define('BZip2', 'BZip2');
define('GZip', 'GZip');
define('Without compression', 'Without Compression');
// ---------------------------------------------------------
// CONTROL
// Most Control defines are found in Admin or Globals.
define("change_event", "Change Event");
define("check", "Check");
define("checked", "Checked");
define("check_success", "Ticket Check OK!");
define("com", "Ordered");
define("control_events_list", "Events");
define("free", "Free");
define("not_valid_event", "Event is Not Valid");
define("place_not_commanded", "Seat is Not Ordered");
define("place_only_reserved", "Seat Reserved, but Not Paid");
define("place_status_not_updated", "Error: Seat Status Not Updated");
define("place_without_nr", "Seat Without Number");
define("ticket_already_checked", "Ticket Already Checked");
define("header_ticket_taker", "Ticket Takers");
define("no_event_sets", "No Events Scheduled");
// ----------------------------------------------
// WEB TEMPLATES (tpl)
// these defines are seperated by tpl file.
// if you can't find the define you are looking for check in Globals.
// if a define is followed by //file.tpl it is also used in that file.
// user_activate.tpl - resend_activation.tpl - user_registred.tpl
define("act_name", "Activate Account"); //checkout_preview
define("act_done", "Account Activated"); //checkout_preview
define("act_enter_title", "Place your Activation Code here:");

define("act_enter_code", "Complete your activation by entering the activation code sent to you by Email in the box below, or click on the link in the Email.<br/>If you have already activated your account, please press [activate] also.");
define("act_code", "Activation code:");
define("act_send", "Send");

define("act_notarr", "Activation Email still not arrived?");
define("resend_activation", "Resend Activation Email");
define("act_is_sent", "Your Activation Email has been sent to your inbox.");
define("act_sent", "The activation Email has been sent. Please check your Email for the activation code or link.");
define("act_mess_line", "Please check your email to activate your account.<br>");
define("act_mess_line_nm", "<b>You may complete this order without activating your account.</b><br>
          					Please check your email to activate your account for future purchases.<br>");

define("act_note", "Enter your e-mail address. Click on 'Send'; and a new activation email will be sent to your e-mail address.");
define("act_norecived", "If you have not received an activation email within a few hours, please click this <strong>link</strong>");
define("success_activate", "You have successfully activated your account.<br /> You may now login.");
// agb.tpl
define("agb_name", "General Terms of Sale");
define("agb_info", "Put your sales conditions in 'agb.html'");
// calendar.tpl
define("calendar", "Calendar");
// cart_content.tpl
define("cart_empty", "Your Cart is Empty.");
define("cart_empty_or_invalid", "The cart is empty or invalid. Please add tickets.");
define("total", "Total");
define("expires_in", "Expires in");
define("total_price", "Total Price"); //personal_order.tpl, personal_orders.tpl

// cart_resume.tpl
define("shopcart", "Shopping Cart");
define("no_tick_res", "You have no reserved tickets.");
define("valid_tickets", "Valid tickets:");
define("expired_tickets", "Expired Tickets:");
define("tick_exp_in", "Expiring in ");
define("x_tick", "x Tickets");
define("tot_tick_price", "Total Ticket Price:");
// cart_subcontent.tpl
define("row", "Row"); //discount.tpl
define("normal", "Normal"); //discount.tpl
define("expired", "EXPIRED");
define("minutes", "min.");
define("selected_total", "Selected Ticket Total in ");
// cart_view.tpl
define("shopping_cart", "Your shopping cart");
define("cart_cont_mess", "Please check your order. You have a limited time before your reserved tickets are released back to sale. Please, either order more tickets or continue to checkout.
	<br><br>To change your tickets 'Remove' the ones you have reserved and click 'Order More Tickets'.");
// cat_description.tpl
define("cat_description", "Seating Sections and Prices");
define("tickets_available", "Availability");
define("category_sold", "Sold out");
define("Discount_for", "With Discount:");
define("prices_in", "Prices in");
define("Please_login", "Please Login or Register to Reserve Seats.");
define("select_category", "Select a Category");
define("order_limit", "Order Limit"); //category.tpl
define("old_event", "<center><B>This event is expired and can not be ordered anymore.</b></center>");
// category.tpl
define("select_seat_head", "Select your seat(s)");
define("select_seat", "1. Select your seat(s)");
define("select_seat_pos", "Select your seat(s) for ");
  define("click_on_reserve", "2.Click on 'Reserve'");
define("number_seats", "Number of seats");
define("only_rows_numbered", "Only rows numbered");
// checkout_confirm.tpl
define("order_reg", "Order Registered");
define("tnx_order_mess", "Thank you very much! Your order was registered. To pay for your order follow the proccess below. ");
// checkout_preview.tpl
define("shopping_cart_check_out", "Select Payment Method");
define("Handling_cont_mess", "Check your tickets, Check your details and then select your payment option and click 'Order'.");
define("handlings", "Payment Methods");
define("payment", "Payment"); //personal_order.tpl
define("shipment", "Shipment"); //personal_order.tpl
define("order_not_found_or_created", "Cannot proceed with order.");
define("limit", "Unfortunately, you have exceeded the amount of allowed reserved tickets.<br> To check your orders click <a href='index.php?action=person_orders'>here</a>.");
define("orclick", " Or Click to:");
define('freeTicketCode', 'Discount Code:');
// checkout_result.tpl
define("pay_accept", "Payment Method Accepted");
define("pay_refused", "Payment Method Not Accepted");
define("pay_reg", "Your payment was successful.");
define("pay_reg_desc", "Your electronic ticket has been sent to your email address.<br/><br/> Please make sure you have a printed copy to present when collecting your physical ticket.<br/><br/><span>Note: if you don't find your electronic ticket in your inbox please check your Spam/Junk folder or <a href='contact.php'>contact us</a>. To avoid our emails being seen as junk, please add our \"From\" address to your Address Book.</span>");
define("pay_error", "There was an error processing your request.");
define("trx_id", "Transaction ID");
define("orderdeleted", "Order Deleted");
define("printinvoice", "Print Invoice");
define("printtickets", "Print Tickets");
// discount.tpl
define("discounts", "Discounts");
define("place_nr", "Seat");
define("unassigned_seat", "Unassigned");
// event.tpl
define("each", "per ticket.");
define("shop", "Order Tickets");
define("shop_main", "Select Event");
define("shop_info", "Check tickets are available for the desired Category then click on 'Buy Tickets' to continue.");
define("shop_info_main", "Select a event below for the day for which you wish to purchase tickets.");
define("shop_condition", "Conditions: Information collected will only be stored to handle shipping and payment.");
define("shop_no_event", "Sorry but the event you are looking for, does not exist, has expired or been removed.");
define("from", "From the");
define("choose_event", "Please select the event for the day you wish to book from the list below:");
// event_description.tpl
define("doors_open", "Doors Open");
define("dates_localities", "Dates and Locations");
define("various_dates", "Multiple Dates");
define("venue", "Location");
define("no_sub_events", "There are no sub-events currently available.");
// event_groups.tpl
define("event_groups", "Event Groups");
// event_type.tpl and event_types.tpl
define("et_church", "Church Music");
define("et_cinema", "Cinema");
define("et_classics", "Classical Music");
define("et_expo", "Expo");
define("et_folk", "Folk");
define("et_humour", "Humour");
define("et_jbf", "Jazz/Blues/Funk");
define("et_music", "Music");
define("et_opbal", "Opera/Ballet");
define("et_other", "Other");
define("et_party", "Party");
define("et_pr", "Pop/Rock");
define("et_theater", "Theatre");
// forgot_password.tpl
define("forgot_pwd", "Forgot Password");
define("pwd_forgot", "Forgotten your Password?");
define("forgot_password", "Forgotten your Password?");
define("forgot_password_ok", "Your password has been reset");
define("forgot_password_ok_desc", "We have generated a new password for you and sent it to your email.");
define("pwd_err", "User not found or other problem occurred");
define("pwd_is_sent", "Your new password was sent to your e-mail");
define("pwd_note", "Enter your e-mail address here. Click on 'Send Password' and a new password will be emailed to you");
// help.tpl
define("helpme", "Help information");
// file is not currently used.  Words need to be defined.
// last_event_list.tpl
define("up_events", "Upcoming Events");
define("eventlist_info", "Please select an upcoming event from below.");
// personal_order.tpl
define("orders", "Orders");
define("number_tickets", "Number of tickets"); //personal_reorder.tpl
define("userid", "User Id");
define("cancelled", "Cancelled");
define("buytimeleft", "You have ~DAYS~ Days, ~HOURS~ Hours, ~MINS~ Mins, ~SECS~ Seconds left to confirm your order!");
define("autocancel", "After this time your order is automatically cancelled!");
define("ordertickets", "To Order Tickets Click below:");
define("reserv_cancel", "WARNING this is a one way process!");
define("notpaid", "Not Paid");

define("paid", "Paid");
define("sold", "Sold");
define("manual_payment", "Set to Paid: ");
define("payhere", "<br><br>To Pay Now, Check Here:");
define("notsent", "Not Sent");
define("sent", "Sent");
define("go_back", "Click Here to Go Back");
define("id", "ID");
define("zone", "Zone");
define("discount", "Discount");

define("paytimeleft", "You have ~DAYS~ Days, ~HOURS~ Hours, ~MINS~ Mins and ~SECS~ Seconds to pay for your order!");
define('paytimeforever','You have to pay for your order, before the start of the event! ');

// personal_orders.tpl
define("pers_orders", "Current / Previous Order");
define("options", "Options");
define("pers_cancel", "Cancelled");
define("pers_ord", "Ordered/Unpaid");
define("pers_page", "Personal Page"); //user_logon_block.tpl
define("pers_paid", "Paid");
define("pers_reissue", "Re-issued");
define("pers_res", "Reserved");
define("pers_send", "Sent & Paid");
define("pers_unknown", "Unknown Status - Contact Admin");
// personal_page.tpl
define("personal", "Personal Details");
define("pers_mess", "To change your details please click <a href='index.php?personal_page=details'>Here</a>");
define("pers_mess2", "To change or see your orders in more detail please click <a href='index.php?personal_page=orders'>Here</a><br><br>Only the last 6 orders are shown here.");
// personal_reorder.tpl
define("payment_status", "Payment Status");
define("shipment_status", "Shipment Status");
// programm.tpl
define("program", "Program");
define("div_dates", "Multiple Dates");
// user_address/tpl
define("your_addr", "Shipment details");
// user_form.tpl
define("confirmemail", "Confirm E-mail Address");
// user_login.tpl
define("login", "Login name");
define("memberinfo", "Please enter your details below. To buy tickets you are required to sign up, it is recommend that you sign up before you reserve tickets.");
define("register", "Register");
// user_login_block.tpl - no unique defines
// user_register.tpl
define("alreadymember", "Already a Member?");
define("alreadymembertext", "to the site with your username and password on your left.");
define("becomemember", "Become a Member");
define("pers_info", "Customer Information");
define("user_notice", "To order your tickets please complete the following fields and click on 'Continue'. Fields with (*) are mandatory. This information will stay confidential. We will not give out your information or sell it to others. You can also choose to become a member and never fill this form again.");
define("becomememberorguest", "Register as Member or Guest");
define("becomeguest", "Register as Guest");
define("guest_info", "Fill your details here, so that the ticket can be sent to you.
					 <br> Already a regular customer? Fill at the left side, your email address and password.");
define("pwd_min", " (min 6 letters)");
define("user_nospam", "Security Code");
define("nospam_info", "Enter this code >>>");
define("agrement", "I have read and agree to the General Terms of Sale.");
define("mayuse", "I agree that {\$organizer->organizer_name} may use my details to send me information about future events and other {\$organizer->organizer_name} related matters, and that they will not pass on my details to any 3rd party.");
define("member_info", "Please enter your email address and password");

define("personaldetails", "Personal Details");
define("addressdetails", "Address Details");
define("contactdetails", "Contact Details");
define("memberpasswords", "Passwords");
define("securitytc", "Security and Terms");
// Progressbar.tpl
define("prg_order", "Order");
define("prg_seat", "Select Seat");
define("prg_discounts", "Select Discounts");
define("prg_review", "Cart");
define("prg_signin", "Register");
define("prg_payment", "Select Payment");
define("prg_complete", "Complete Order");
// ---------------------------------------------------------
// POS
// checkout.php
define("No_handling_selected", "No handling method selected.");
define("noting_checkout", "There is nothing to checkout!!!");
define("No_useraddress_selected", "No user address selected.");
// Errors
define("error_missingcategorytype", "Missing category numbering type");
define("error_discountnotfound", "Discount not found");
// event_group.tpl
define("event_sold", "Event sold out");
// datetimeselect.php
define("dts_month_arr", "January|February|March|April|May|June|July|August|September|October|November|December");
// handlings.tpl
define("without_fee", "Without Fee");
// header.tpl
// define("box_office","Box Office");
// define("ajax_beta","<i>[AJAX Beta]</i>");
define("box_office", "<h2>Box Office</h2>");
// order.tpl
define("alert", "Alert:");
define("pos_booktickets", "Book Tickets"); //header.tpl
define("seat_count", "Seat Count:");
define("pos_order_page", "Order Checkout");
define('free_seat', 'Seats Available:');
// personal_order.tpl
define('open_event', 'Open Event?');
// process_list.tpl
define("actions", "Actions");
define("timestamp", "Timestamp");
// process.menu.tpl
define("cancel_and_delete", "Status Changed to 'Cancelled' and Tickets Deleted");
define("order_details", "Order details");
// process_select.tpl
define("order_status_changed", "Order Status Changed Successfully");
define("pos_currenttickets", "Overview"); //header.tpl
define("pos_yourtickets", "Owned Orders");
define("pos_alltickets", "All Paid/Sent Orders");
define("pos_paidlist", "Paid orders");
define("pos_unsentlist", "UnSent Orders");

define("pos_unpaidlist", "UnPaid List");
define("pos_reservedlist", "Reserved Orders");
// process_view.tpl
define("paymentstatus", "Payment Status");
define("pos_clicktoproc", "Click To Finish Processing!");
define("pos_clicktosent", "Click To Set to Sent!");
define("pos_deleteorder", "Delete Order?");
define("pos_handlinginfo", "Handling Info");
define("pos_enternote", 'Notes about order and tickets:<br><b>Remember to save after any changes!!</b>');
define("pos_goback", "Click to go back");
define("pos_nextorder", "Next Order");
define("pos_nextunpaid", "Next UnPaid Order");
define("pos_nextunsent", "Next UnSent Order");
define("pos_processorder", "Processed Order? The Order ID and Ticket IDs been noted?");
define("pos_reorder_info", "To Order Tickets Click below:<br>WARNING old order is cancelled on Re-Order!");
define("pos_ticketready", "The tickets have there ID and Order ID on them and are ready to to be posted?");
define("shipmentstatus", "Shipment Status");
define("next_order_id", "Next Order ID");
// process_view.tpl; view.tpl; view_reorder.tpl
define("cancel_ticket", "Cancel Ticket");
define("reissued", "Re-issued");
// user.tpl
define("exist_user", "Existing");
define("new_partron", "New");
// view_options.tpl
define("open_with_acrobat", "Open Orders in Acrobat");
define("preferences", "Preferences");
define("send_orders_printer", "Send Orders to Printer");

define("select_country", "Select Country...");
define("eph_cash_confirm", "The order is created, press 'Pay' to continue.");
// ------------------------------------------------------
// Not quite sure about these.  8/26/09 Lou
// class.adminview.php - These are in the file but I can not find where they display
define("page_format_default", "Default");
define("page_format_std", "Standard");
define("page_format_custom", "Custom");
define("page_orientation_landscape", "Landscape");
define("page_orientation_portrait", "Portrait");
// function.placemap.php
define("place_row", "Row");
define("place_seat", "Seat");
// EventViewCommon.php
define("payment_cc", "Credit Card");
define("payment_post", "Post");
// eph_paypal.php
define("paypal_pay", "Make payments with PayPal - it's fast, free and secure!");
// OrtView.php
define("pm_title", "Charts");
// --------------------------------------------------
// Html2pdf Templates
define("tmp_receipt", "Receipt");
define("tmp_invoice", "Invoice");
define("tmp_sold_to", "Sold To:");
define("tmp_order_date", "Order Date");
define("tmp_order_number", "Order Number");
define("tmp_order_method", "Order Method");
define("tmp_shipping_method", "Shipping Method ");
define("tmp_payment_method", "Payment Method");
define("tmp_description", "Description");
define("temp_quantity", "Qty.");
define("tmp_category", "Category ");
define("tmp_discounts", "Discounts");
define("tmp_price", "Price");
define("tmp_total", "Total ");
define("tmp_subtotal", "SubTotal:");
define("tmp_fee", "Handling Fee:");
define("tmp_order_total", "Total:");
define("tmp_thank_you", "Thank You!");
define("tmp_inv_message", "Your tickets have been reserved. Please pay this invoice within 10 minutes.");
// Tickets
define("tmp_flourish", " Presents");
define("tmp_venue", "Location Details");
define("tmp_date_time", "Date and Time");
define("tmp_event_date", "Event Date:");
define("tmp_start_time", "Start Time: ");
define("tmp_open_time", "Doors Open: ");
define("tmp_section", "Seating Section");
define("tmp_zone", "Zone:");
define("tmp_row", "Row #: ");
define("tmp_seat", "Seat#: ");
define("tmp_details", "Ticket Details");
define("tmp_type", "Type: ");
define("tmp_discount", "Discount: ");

define("tmp_conditions", "<b>NOTE TO PURCHASER: TREAT THIS E-TICKET AS YOU WOULD ANY OTHER VALUABLE OR CASH. YOU WILL NEED TO PRINT THIS AND PRESENT IT WHEN COLLECTING YOUR PHYSICAL TICKET.</b>
Unauthorised duplication, alteration, or sale of this ticket may prevent admittance to the event.
Present this page at the time of admission to be scanned.
The unique bar code on this ticket allows only one entry per ticket.");

define('login_pos', 'Login POS');
define('login_organizer', 'Login Administration');
define('login_control', 'Login Ticket Taker');

define('approx', 'Approx');

define('English', 'English');

define('pay', 'Pay');
// model.order
define('orderDescription', 'Fusion Ticket Order Description');

define('not_all_all_values_set', 'not_all_all_values_set');
define('email_compile_error', 'Compile error: ');
// define('print-tooltip','Print');
// define('unpublish-tooltip','Unpublish');
// define('add-tooltip','add-tooltip');
// define('view-tooltip','view-tooltip');
// define('edit-tooltip','edit-tooltip');
// define('details-tooltip','View Details');
// define('remove-tooltip','remove-tooltip');
// define('send_test-tooltip','send_test-tooltip');
// define('admin_list-tooltip','admin_list-tooltip');
define ('order_discount_price', 'Discount amount');
define ('order_partial_price', 'Partial amount');
define ('discount_promo', 'Discount promo code');
define ('Global_discounts', 'Order Discounts');
define ('down_install_again', 'Install new version');
define ('version_checker', 'Install new version');
define ('avaliable_version', 'Available version');
define ('curr_ver', 'Current version');
define ('curr_build', 'Current build');
define ('version_missing', 'missing');
define ('are_you_sure', 'Do you want to download update?');
define ('are_you_sure_update', 'Do you want to download update?');
define ('are_you_sure_apply_update', 'Do you want to apply update? YOU WILL LOOSE CUSTOM CHANGES!');
define ('shopconfig_ftusername', 'fusionticket.org Username');
define ('shopconfig_ftpassword', 'fusionticket.org Password');
define ('shopconfig_keepdetails', 'Save User/Pass/Proxy Settings?');
define ('download_update', 'Update Settings and Apply');
define ('shopconfig_proxyaddress', 'Updater optional proxy address.');
define ('shopconfig_proxyport', 'Update proxy port.');
define ('downloaded_update_files', 'Downloaded Update Files:');
define ('download_older_than_curr', 'Available Update is older than currently installed!');
define ('download_same_as_curr', 'Available Update is same as currently installed!');
define ('download_link_failed', 'Could not get download link');
define ('file_download_failed', 'Failed to download file, try again later');
define ('file_downloaded', 'Update.zip downloaded');
define ('file_saved', 'Update.zip Saved');
define ('file_deleted', 'Finished with Update.zip, removed.');
define ('update_save', 'Update and/or Save Details');

define ('version_updater', 'Update');
define ('order_already_crated', "This order is already saved!!!");
define ('Select_handling_option', 'Select a payment option.');
define ('FreeTicketCode_notfound', 'FreeTicketCode not found. Try again');
define ('placemap_image_explanation', "<img class='seatmapimage' src='{$_SHOP->images_url}seatfree.png'>: Available choice,
                                     <img class='seatmapimage' src='{$_SHOP->images_url}seatselect.png'>: Selected,
                                     <img class='seatmapimage' src='{$_SHOP->images_url}seatused.png'>:  Unavailable,
                                     <img class='seatmapimage' src='{$_SHOP->images_url}seatdisable.png'>: Other category");
define ('copy_main_pm', 'copy from main event');
define ('republish_old_event', "Are you sure you want to publish this archived event?\r\n It will only show in the [Program] area of the Web Shop.");
define('UnPublished', 'UnPublished');
define('restart__failure', 'restart__failure');
define('plugins_tab', 'Plugins');
define('category_pm_id', 'Seat Chart ID');
define('category_pmp_id', 'Seat Chart Part ID');
define('publish7', 'publish7');
define('pub__failure', 'The following event failed to publish:');
define('export_entrant_withseats', 'Export with seat numbers');

define('mollie_status_cancelled', 'You have cancelled the order while in iDeal');
define('always_show_handling', 'Show always');
define('shopconfig_maxorder', 'Max tickets to order');
// 08-02-2010
define('enabled', 'Enabled');
define('InfoGetFileFunction', 'InfoGetFileFunction');
define('InfoZipLibInstalled', 'InfoZipLibInstalled');
define('show_stat_reports', 'Reports');
define('show_stat_reports', 'Other Reports');

define('report_admin_title', 'Spreadsheet Reports');
define('report_xl', 'Sales Report');
define('report_archive_event', 'Event Archives');
define('report_entrant', 'Ticket Status Report');
define('export_statuslog', 'Export Status Log');
define('max_seats_reached', 'Maximum Seats Reached');
define('handling_alt_settings', 'Alternate Settings');

define('Show always', 'Show Always');

define('Hide', 'Hide');

define('handling_fee_settings', 'Fee Settings');

define('hanging_pdf_settings', 'PDF Settings');

define('hanging_email_settings', 'Email Settings');

define('plugins_title', 'Plug Ins');

define('plugin_email', 'Email: ');

define('plugin_web', 'Website: ');

define('order_status_send', 'Sent');
define('promo_in_use', 'This promocode is already used.');
define('discount_promo_for', 'Discount promocode for&nbsp;');
// Sales Data Report
define('rep_ord_id', 'Order #');
define('rep_sale_date', 'Sale Date');
define('rep_sale_barcode', 'Barcode');
define('hand_payment_text', 'Payment Method');

define('user_title', 'Customer Information');
define('cust_id', 'ID');

define('ven_id', 'ID');
define('ven_name', 'Name');

define('evnt_title', 'Event Information');
define('evnt_id', 'ID');
define('evnt_name', 'Name');
define('evnt_date', 'Date');
define('evnt_time', 'Time');

define('cat_title', 'Ticket Information');
define('cat_id', 'ID');
define('cat_name', 'Name');
define('cat_price', 'Value');
define('cat_sold', 'Sold For');

define('disc_name', 'Name');
define('disc_type', 'Type');
define('disc_value', "Value");

define('handling_fee_type', 'Fee calculation type');
define('handling_fee_sum', 'Sum of fixed and Percent');
define('handling_fee_min', 'Minimum of fixed and Percent');
define('handling_fee_max', 'Maximum of fixed and Percent');

define('pay_ispending', "Payment transaction is pending.");
define('cookie_disabled', '<h1> Warning, you have disabled cookies on your system.</h1> This will not work on this website. Please turn this on, before you order any tickets.</h1>');

define('admin_user_id', 'Sale Point user');
define('admin_status', 'Role of user');
define('admin_email', 'Email address');
define('admin_posuser_title', "Sale Point Administrators");
define('user', 'user');
define('NotPaid', 'Not paid');
define('Shipment', 'Shipment');
define('shipment_type', 'Shipment Type');
define('shipment_address', 'Shipment Address');
define('order_owner_id', 'Create by POS');

define('onote_type_note' , 'note');
define('onote_type_admin' , 'admin');
define('onote_type_payment', 'payment');
define('onote_type_todo' , 'todo');
define('onote_isprivate', ' (<i>private</i>)');
define("onote_private", "Personal Note (You can see only)");
define("onote_subject", "Note Subject");
define("onote_note", "Note Details");
define("onote_set_sent", "On \"Save &amp; Email\" Set Order To Sent.");
define("onote_set_paid", "On \"Save &amp; Email\" Set Order To Paid.");
define("onote_resend_sent", "Resend \"Sent\" Email.");
define("onote_resend_paid", "Resend \"Paid\" Email.");

define("logged_in", "User:");
// Events
define("placemap", "Event Placemap");

define("event_details", "Event Details");
define("event_view_periode", "Event Viewing Period");
define("event_view_begin_date", "Event View Begin Date");
define("event_view_begin_time", "Event View Begin Time");
define("event_view_end_date", "Event View End Date");
define("event_view_end_time", "Event View End Time");

define('placemap', 'placemap');
define('discount_deactivate', 'Deactivate Discount');
define('discount_activate', 'Activate Discount');
define('print_invoice', 'Print invoice');
define('print_tickets', 'Print tickets');
define("block", "Block Seats");
define("assign", "Assign Seats");
define("clear_selection", "Clear Selection");
define("reset", "Reset");
define("confirm_clear", "Are you sure you want to clear your selection?");
define("delete_link_error", "Can't delete an admin link when there are seats associated with this pos");
// Events Mailer
define("mailer_description", "Use this interface to send emails to customers attending this event.");
define("mail_patrons_confirm", "Are you sure you want to email all attending customers?");
define("mail_patrons", "Mail Customers");
define("select_template", "Select template");
define("mail_to", "Mail to");
define("attending_patrons", "attending customers");
define("preview_template", "Preview template");
define("please_select_template", "Please select a template.");
define("send_email", "Send Email");
define("include_invoice", "Include Invoice");
define("include_tickets", "Include Tickets");

define('cant_create_seat_zone_missing_cat', 'Every zone needs to have category in a placemap part.');
define('pub_success', 'The following event has been successfully published: ');
define('pm_vpc_AccessCode', 'pm_vpc_AccessCode');
define('pm_vpc_Merchant', 'pm_vpc_Merchant');
define('pm_vpc_SECURE_SECRET', 'pm_vpc_SECURE_SECRET');
define('vpc_pay', 'vpc_pay');
define('successfully_updated_user_details', 'Your profile has been successfully updated.');
define('payment_not_approved', 'payment_not_approved');

define("cc_name", "Credit card owner name");
define("cc_number", "Credit card number");
define("cc_exp", "Expiry date");
define("cc_code", "Security code");

define('control', 'control');
define('error', 'error');

define('event_details', 'Event details');
define('event_view_periode', 'Scheduled publishing');
define('event_view_begin_date', 'Published start date');
define('event_view_begin_time', 'Published start time');
define('event_view_end_date', 'Published end date');
define('event_view_end_time', 'Published end time');
define('select_qty', 'Select quantity');
define('successfully_add_note', 'successfully_add_note');
define('plugin_allow_uninstall', 'plugin_allow_uninstall');

define('please_select', 'please select');
define('venue_in_use', 'This venue is in use.');

define('print_both', 'Print invoice & ticket');
define('view_details', 'View order details');
define('cannot_find_order', 'Cannot find order');

define('main', 'main');
define('control', 'Ticket Taker');
define('ticket', 'ticket');

define('failed_send_to', 'Failed to send an Email.');
define('admin_status_control', 'Ticket taker');
define('admin_status_pos', 'POS User');
define('admin_status_posman', 'POS Manager');
define('admin_status_organizer', 'Organizer');
define('admin_status_accountant', 'Accountant');
define('admin_status_admin', 'Administrator');

define('role_not_allowed', 'Role not allowed.');

define('ERROR_NOSIZEDIFF', 'No new size given.');

define('category_nosale_size', 'Category Size');

define('plugin_install_title', 'plugin_install_title');
define('plugin_name', 'Pluginname');
define('plugin_info', 'Plugin information');
define('plugin_myversion', 'Version number');
define('plugin_priority', 'Priority');
define('plugin_priority_5', '5');
define('plugin_priority_4', '4');
define('plugin_priority_3', '3');
define('plugin_priority_2', '2');
define('plugin_priority_1', '1');
define('plugin_enabled', 'Enabled');
define('plugin_protected', 'Protected plugin');

define('discount_www_deactivate', 'Deactivate this Discount for the webshop?');
define('discount_pos_deactivate', 'Deactivate this Discount for the Points of Sale?');
define('discount_www_activate', 'Activate this Discount for the webshop?');
define('discount_pos_activate', 'Activate this Discount for the Points of Sale?');

define('discount_www_activated', 'Active in the webshop');
define('discount_pos_activated', 'Active in the Points of Sale');
define('discount_pos_deactivated', 'Deactive in the Points of Sale');
define('discount_www_deactivated', 'Deactive in the webshop');

define('handling_www_deactivate', 'Deactivate this handling for the webshop?');
define('handling_pos_deactivate', 'Deactivate this handling for the Points of Sale?');
define('handling_www_activate', 'Activate this handling for the webshop?');
define('handling_pos_activate', 'Activate this handling for the Points of Sale?');

define('handling_www_activated', 'Active in the webshop');
define('handling_pos_activated', 'Active in the Points of Sale');
define('handling_pos_deactivated', 'Deactive in the Points of Sale');
define('handling_www_deactivated', 'Deactive in the webshop');

define('nextbutton', 'Next');

define('handling_sp', 'POS');
define('handling_www', 'WEB');

define('for_handling_salemode', 'for: ');
define('you_requested_that_is_not_visable', 'you_requested_that_is_not_visible');
define('you_do_not_have_access', 'you_do_not_have_access');

define('order_seats', 'order_seats');
define('templ_files', 'templ_files');
define('discount_used', 'discount_used');

define('plugin_config_title', 'plugin_config_title');
define('plugin_fts_username', 'plugin_fts_username');
define('plugin_fts_password', 'plugin_fts_password');
define('plugin_fts_comm_error', 'plugin_fts_comm_error');
define('plugin_fts_user', 'plugin_fts_user');
define('plugin_fts_servertoken', 'plugin_fts_servertoken');

define('pub_failure', 'pub_failure');
define('error_event_save_changes', 'error_event_save_changes');
define('stop_failure', 'stop_failure');
define('restart_failure', 'restart_failure');
define('order_status_ordered', 'order_status_ordered');

define('admin_default', 'Default');
define('reminders_list', 'Reminders');
define('reminder_title', 'List of all reminders');
define('reminder_form_title', 'Reminder settings');

define('reminder_name', 'Reminder title');
define('reminder_ident', 'Reminder weight');
define('reminder_days', 'days before reminder');
define('reminder_email', 'eMail template');
define('reminder_pdf', 'PDF template');
define('reminder_fee', 'Reminder fee');
define('reminder_text', 'Description');
define('reminder_color', 'Color');
define('reminder_id', 'Reminder id');

define('reminder_ident_th', 'Weight');
define('reminder_name_th', 'Name');
define('reminder_days_th', 'Days');
define('reminder_email_th', 'eMail');
define('reminder_pdf_th', 'PDF');
define('reminder_fee_th', 'Fees');

define('reminder_activate_question', 'Do you want to activate this reminder?');
define('reminder_deactivated', 'Deactivated');
define('reminder_deactivate_question', 'Do you want to deactivate this reminder?');
define('reminder_activated', 'Activated');

define('reminders_orders', 'Reminders');

define('reminder_activate', 'Activate reminder ');
define('reminder_order_question', 'Send the reminder now?');
define('send_reminder', 'Send reminder');

define('reminder_order_id_th', 'Order');
define('reminder_order_date_th', 'Order date');
define('reminder_order_total_th', 'Total price');
define('reminder_order_reminder_th', 'Reminders');
define('reminder_next_to_be_send_in', 'next in ');
define('reminder_next_days', ' days.');

define('reminder_need_to_be_send', ' needs to be sent!');
define('reminders_order_title', 'Show All unpaid orders!');

define('reminder_is_send_on', ' is send on ');

define('eProcessingNetwork','eProcessingNetwork');
define('maroc','maroc');
define('maroctelecommerce','maroctelecommerce');
define('mollie','mollie');
define('vpc','vpc');
define('pm_maroc_SecretKey','pm_maroc_SecretKey');
define('pm_maroc_storeId','pm_maroc_storeId');
define('no_event_data','no_event_data');
define('pm_maroc_url','pm_maroc_url');
define('pm_mollie_partnerid','pm_mollie_partnerid');
define('pm_mollie_test','pm_mollie_test');
define('bank_id','bank_id');
define('Uninstall','Uninstall');
define('Install','Install');

define('Bill','Bill');
define('Others','Others');
define('report_eventbarcodes','List of Barcodes per event');
define('export_eventbarcodes_title','List of Barcodes per event');
define('export_eventbarcodes_event','Select Event');
define('eventbarcodes_date','Date');
define('eventbarcodes_time','Time');
define('export_eventbarcodes_code','Barcode');
define('export_eventbarcodes_fullname','Patron');
define('entrant_date','Date');
define('entrant_time','Time');
define('nav_first-tooltip','');
define('nav_prev-tooltip','');
define('nav_next-tooltip','');
define('nav_last-tooltip','');
define('select_seat_title','Select seat');
define('ismember','ismember');
define('captcha_info','Enter this code >>>');
define('user_condition','"Conditions: Information collected will only be stored to handle shipping and payment."');
define('pdf_tickets_and_invoice','pdf_tickets_and_invoice');
define('home','home');
define('order_is_not_paid','Order not Paid.');
define('seat_checked','Seat checked');
define('event_ordering','Order tickets');
define('event_ordering-tooltip','');
define('event_select_cat','Select Category');
define('event_select_seats','Seatmap');
define('select_discounts','Select Discount');
define('no_discount','No discount');
define('bad_category_id','Category not found.');
define('next','next');
define('add_to_cart','Add to Cart');
define('error_cantloadevent','can\'t load selected event');
define('buy_tickets','Buy Tickets');
define('input_fields_requered','Input fields with a <b>*</b> are required.');
define('person_user','Profile details');
define('person_orders','List of orders');
define('printticket','Print Ticket(s)');
define('pdf_invoice_only','pdf_invoice_only');
define('with_discount',' with ');
define('edit_user','Edit');
define('user_update','Save profile');
define('failed_too_update_user','failed to update userprofile');
define('order_info','Order overview');
define('invalid_order','invalid_order');
define('submit_reserve','Reserve');
define('order_allready_cancelled','Order ilready cancelled.');
?>
<?php
define('go_home','go_home');
?>
<?php
define('go_pay','go_pay');
?>
<?php
define('user_email2','Repeat E-Mail');
?>
<?php
define('status_change_handling_error','status_change_handling_error');
define('order_reserved','Order is reserved for you');
?>
<?php
define('cart_expired','The tickets in you cart are expired and will be removed from the cart.');
define('vertical_menu','Menu');
define("no_orderid_given", "No Order ID for ticket loading");

define('admin_status_adminz','!!admin_status_adminz!!');
?>
<?php
define('select_user_country','!!select_user_country!!');
define('select_user_country','&nbsp;<font size=-1>Check/Uncheck All&nbsp;&nbsp; </font>');
define('cant_lock_seat','can0 lock seat');
define('Cant_find_seat','Cant find seat');
define('search_choice_two_fields','Fill atliest 2 fields.');
define('personal_search_dialog','Personal Search dialog');
?>