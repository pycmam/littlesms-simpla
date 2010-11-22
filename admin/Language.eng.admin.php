<?php
class Language
{

#General messages
var $HOME_TEXT = 'home';
var $EMPTY_LIST = 'empty';
var $EDIT = 'Edit';
var $MOVE_UP = 'Move up';
var $MOVE_DOWN = 'Move down';
var $DELETE_SELECTED = 'Delete selected';
var $SAVE = 'Save';
var $ARE_YOU_SURE_TO_DELETE = 'Are you sure to delete selected items?';


#MainPage
var $NEWS = 'News';
var $ARTICLES = 'Articles';
var $POLLS = 'Опросы';
var $SETTINGS = 'Settings';
var $PRODUCTS_CATEGORIES = 'Products categories';
var $PRODUCTS = 'Products';
var $ORDERS = 'Orders';
var $PRICELIST_IMPORT = 'Pricelist import';
var $PRICELIST_EXPORT = 'Pricelist export';
var $PRODUCTS_IMPORT = 'Products import';
var $CURRENCIES = 'Currencies';
var $STATISTICS = 'Statistics';
var $USERS = 'Users';
var $USERS_CATEGORIES = 'Users categories';
var $USERCOMMENTS = 'Comments';
var $GOOGLE_SITEMAP = 'Google Sitemap';

#PageNavigation
var $PAGES = 'Pages';

#Sections
var $SECTIONS = 'Sections';
var $NEW_SECTION = 'New section';
var $NAME = 'Name';
var $HEADER = 'Header';
var $SECTION_TYPE = 'Section type';
var $PAGE_URL = 'Page URL';
var $ENTER_SECTION_NAME = 'Enter section name';
var $ENTER_SECTION_HEADER = 'Enter section header';
var $ENTER_PAGE_URL = 'Enter page URL';
var $EDIT_SECTION = 'Edit section';
var $TITLE = 'Title';
var $DESCRIPTION = 'Description';
var $KEYWORDS = 'Keywords';
var $PAGE_TEXT = 'Page text';
var $TARGET_URL = 'Target URL';

var $SECTION_WITH_SAME_URL_ALREADY_EXISTS = 'Section with the same URL already exists. Please enter another URL.';

#News
var $NEW_NEWS_ITEM = 'New item';
var $EDIT_NEWS_ITEM = 'Edit item';
var $DATE = 'Date';
var $ANNOTATION = 'Annotation';
var $NEWS_TEXT = 'Text';
var $ENTER_TITLE = 'Enter title';
var $ENTER_CORRECT_DATE = 'Enter correct date';

#Articles
var $NEW_ARTICLE = 'New article';
var $EDIT_ARTICLE = 'Edit article';
var $ARTICLE_TEXT = 'Article text';


#Storefront
var $CATEGORIES = 'Categories';
var $BRANDS = 'Brands';
var $NEW_PRODUCT = 'New product';
var $EDIT_PRODUCT = 'Edit product';

var $CATEGORY = 'Category';
var $BRAND = 'Brand';
var $ALL = 'All';
var $MODEL = 'Model';
var $HIT = 'Hit';
var $PRICE = 'Price';

var $GUARANTEE = 'Guarantee';
var $IN_STOCK = 'In stock';
var $ACTIVE = 'Active';
var $DELETE = 'Delete';
var $IMAGE = 'Image';
var $SMALL_IMAGE = 'Small image';
var $LARGE_IMAGE = 'Large image';
var $ADDITIONAL_IMAGES = 'Additional images';
var $SAVE_CHANGES = 'Save changes';

var $SHORT_DESCRIPTION = 'Short description';
var $FULL_DESCRIPTION = 'Full description';
var $PHOTO = 'Photo';
var $BACK = 'Back';

var $ENTER_BRAND = 'Enter brand';
var $ENTER_MODEL = 'Enter model';

var $FILE_UPLOAD_ERROR = 'File upload error';

#Storefront categories
var $CAT_ENABLED = 'Enabled';
var $CAT_DISABLED = 'Disabled';
var $NEW_CATEGORY = 'New category';
var $EDIT_CATEGORY = 'Edit category';
var $PARENT_CATEGORY = 'Parent category';
var $ROOT_CATEGORY = 'Root category';
var $ENTER_NAME = 'Enter name';

#Users
var $LOGIN = 'Login';
var $USER_CATEGORY = 'Category';
var $USERNAME = 'Name';
var $ADDRESS = 'Address';
var $PHONE = 'Phone';
var $ORDERS_HISTORY = 'Orders history';
var $UNDEFINED_CATEGORY = 'Undefined';

var $EDIT_USER = 'Edit user';
var $PASSWORD = 'Password';
var $USER_ENABLED = 'Enabled';
var $USER_ENABLE = 'Enable';
var $USER_DISABLE = 'Disable';
var $APPLY_FILTER = 'Apply filter';

#Users categories
var $DISCOUNT = 'Discount';
var $NEW_USERS_CATEGORY = 'New category';
var $EDIT_USERS_CATEGORY = 'Edit category';


#Orders

var $COMMENT = 'Comment';
var $STATUS = 'Status';
var $PCS = 'pcs';
var $IS_IN_STOCK = 'in stock';
var $NOT_IN_STOCK = 'not in stock';
var $CHANGE_STATUS = 'Change status';
var $NEW_STATUS = 'New';
var $ACCEPTED_STATUS = 'Accepted';
var $DONE_STATUS = 'Done';
var $EDIT_ORDER = 'ђедактирование заказа';

#Statistics

var $LAST_YEAR = 'Last year';
var $LAST_MONTH = 'Last month';
var $LAST_WEEK = 'Last week';
var $YESTERDAY = 'Yesterday';
var $TODAY = 'Today';
var $CLEAR_STATISTICS = 'Clear statistics';

var $OLDER_THAN_YEAR = 'Older than year';
var $OLDER_THAN_MONTH = 'Older than month';
var $OLDER_THAN_WEEK = 'Older than week';
var $OLDER_THAN_DAY = 'Older than day';
var $CLEAR_ALL = 'Clear all';
var $STATISTICS_FOR = 'Statistics for';
var $PRODUCT = 'Product';
var $GO_TO_MAIN_PAGE = 'Go to main page';

#Comments
var $USERCOMMENT = 'Comment';
var $POINTS = 'Points';

#Google sitemap
var $GENERATE = 'Generate';

#Pricelist import
var $UPDATE_PRICES = 'Update prices';
var $PRICELIST_IMPORT_HELP = 'Please copy from your Excel pricelist columnt with brand, model and price and paste it to form below';
var $IMPORT_RESULTS = 'Import results';
var $OLD_PRICE = 'Old price';
var $NEW_PRICE = 'New price';
var $NO_PRICES_IMPORTED = 'No prices imported. Please check your pricelist.';
var $UPDATED = 'Updated';
var $PRODUCT_NOT_FOUND = 'Product not found';

#Pricelist export
var $PRICELIST_EXPORT_IN_VARIOUS_FORMATS = 'Pricelist export in various formats';
var $FORMAT = 'Format';
var $PRICELIST_URL = 'Pricelist URL';
var $DOWNLOAD = 'Download';

#Products import
var $IMPORT_FROM = 'Import from';
var $IMPORT_SHOPSCRIPT_HELP = 'Choose the <nobr>"Каталог -> <nobr>Экспорт товаров в CSV"</nobr> menu on old site and create CSV file. Also copy product fotos to <i><nobr>foto/storefront/</nobr></i> folder on this site';
var $PRODUCTS_FILE = 'Products file';
var $START_IMPORT = 'Start import';
var $PRODUCTS_IMPORT_RESULTS = 'Results';
var $PRODUCT_ADDED = 'Added';
var $PRODUCT_EXISTS = 'Exists';

#Currencies
var $MAIN_CURRENCY = 'Main';
var $SIGN = 'Sign';
var $RATE = 'Rate';
var $CODE = 'Code ISO 3';
var $ADD_CURRENCY = 'Add currency';
var $CURRENCY = 'Currency';

#Settings
var $SITE_NAME = 'Site name';
var $SITE_NAME_HELP = '';
var $COMPANY_NAME = 'Company name';
var $COMPANY_NAME_HELP = 'Used in pricelist export in Yandex XML format';
var $ADMIN_EMAIL = 'Administrator email';
var $ADMIN_EMAIL_HELP = 'Email for getting orders';
var $MAIN_PAGE = 'Main page';
var $MAIN_PAGE_HELP = '';
var $META_TITLE = 'Meta title';
var $META_TITLE_HELP = 'Meta title for main page';
var $META_KEYWORDS = 'Meta keywords';
var $META_KEYWORDS_HELP = 'Meta keywords for main page';
var $META_DESCRIPTION = 'Meta description';
var $META_DESCRIPTION_HELP = 'Meta description for main page';
var $PHONES = 'Phones';
var $PHONES_HELP = '';
var $COUNTERS_CODE = 'Counters code';
var $COUNTERS_CODE_HELP = 'Paste counters codes here';
var $FOOTER_TEXT = 'Footer text';

#Polls
var $POLL = 'Poll';
var $VOTES_NUMBER = 'Points';
var $NEW_POLL = 'New poll';
var $QUESTION = 'Question';
var $ANSWER = 'Answer';
var $NEW_ANSWER = 'Add choise';
var $VOTE = 'Vote';

#Backups
var $BACKUP = 'Backup';

#Payment Methods
var $PAYMENT_METHODS = 'Payment Methods';
var $USE_METHODS = 'Payment Methods';



var $LOGOUT = 'Logout';

}

?>