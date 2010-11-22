<?php
class Language
{

#General messages
var $HOME_TEXT = 'На главную';
var $EMPTY_LIST = 'Пусто';
var $EDIT = 'Редактировать';
var $MOVE_UP = 'Вверх';
var $MOVE_DOWN = 'Вниз';
var $DELETE_SELECTED = 'Удалить выбранные';
var $SAVE = 'Сохранить';
var $ARE_YOU_SURE_TO_DELETE = 'Подтвердите удаление';


#MainPage
var $NEWS = 'Новости';
var $ARTICLES = 'Статьи';
var $POLLS = 'Опросы';
var $SETTINGS = 'Настройки';
var $PRODUCTS_CATEGORIES = 'Категории товаров';
var $PRODUCTS = 'Товары';
var $ORDERS = 'Заказы';
var $PRICELIST_IMPORT = 'Импорт прайса';
var $PRICELIST_EXPORT = 'Экспорт прайса';
var $PRODUCTS_IMPORT = 'Импорт товаров';
var $CURRENCIES = 'Валюты';
var $STATISTICS = 'Статистика';
var $USERS = 'Пользователи';
var $USERS_CATEGORIES = 'Группы пользователей';
var $USERCOMMENTS = 'Отзывы';
var $GOOGLE_SITEMAP = 'Google Sitemap';

#PageNavigation
var $PAGES = 'Страницы';

#Sections
var $SECTIONS = 'Разделы';
var $NEW_SECTION = 'Новый раздел';
var $NAME = 'Название';
var $HEADER = 'Заголовок';
var $SECTION_TYPE = 'Тип раздела';
var $PAGE_URL = 'URL страницы';
var $ENTER_SECTION_NAME = 'Введите название';
var $ENTER_SECTION_HEADER = 'Введите заголовок';
var $ENTER_PAGE_URL = 'Введите URL страницы';
var $EDIT_SECTION = 'Редактирование раздела';
var $TITLE = 'Meta title';
var $DESCRIPTION = 'Meta description';
var $KEYWORDS = 'Meta keywords';
var $PAGE_TEXT = 'Текст страницы';
var $TARGET_URL = 'Целевой URL';

var $SECTION_WITH_SAME_URL_ALREADY_EXISTS = 'Раздел с таким URL существует. Выберите другой URL.';

#News
var $NEW_NEWS_ITEM = 'Добавить новость';
var $EDIT_NEWS_ITEM = 'Редактирование новости';
var $DATE = 'Дата';
var $ANNOTATION = 'Аннотация';
var $NEWS_TEXT = 'Текст';
var $ENTER_TITLE = 'Введите заголовок';
var $ENTER_CORRECT_DATE = 'Введите правильную дату';

#Articles
var $NEW_ARTICLE = 'Новая статья';
var $EDIT_ARTICLE = 'Редактирование статьи';
var $ARTICLE_TEXT = 'Текст статьи';


#Storefront
var $CATEGORIES = 'Категории';
var $BRANDS = 'Производители';
var $NEW_PRODUCT = 'Новый товар';
var $EDIT_PRODUCT = 'Редактирование товара';

var $CATEGORY = 'Категория';
var $BRAND = 'Производитель';
var $ALL = 'Все';
var $MODEL = 'Модель';
var $HIT = 'Хит';
var $PRICE = 'Цена';

var $GUARANTEE = 'Гарантия';
var $IN_STOCK = 'На складе';
var $ACTIVE = 'Активен';
var $DELETE = 'Удалить';
var $IMAGE = 'Изображение';
var $SMALL_IMAGE = 'Маленькое изображение';
var $LARGE_IMAGE = 'Большое изображение';
var $ADDITIONAL_IMAGES = 'Дополнительные изображения';
var $SAVE_CHANGES = 'Сохранить изменения';

var $SHORT_DESCRIPTION = 'Краткое описание';
var $FULL_DESCRIPTION = 'Полное описание';
var $PHOTO = 'Фото';
var $BACK = 'Назад';

var $ENTER_BRAND = 'Введите производителя';
var $ENTER_MODEL = 'Введите модель';

var $FILE_UPLOAD_ERROR = 'Ошибка загрузки файла';

#Storefront categories
var $CAT_ENABLED = 'Активна';
var $CAT_DISABLED = 'Неактивна';
var $NEW_CATEGORY = 'Новая категория';
var $EDIT_CATEGORY = 'Редактирование категории';
var $PARENT_CATEGORY = 'Родительская категория';
var $ROOT_CATEGORY = 'Корень';
var $ENTER_NAME = 'Введите название';

#Users
var $LOGIN = 'Логин';
var $USER_CATEGORY = 'Группа';
var $USERNAME = 'Имя';
var $ADDRESS = 'Адрес';
var $PHONE = 'Телефон';
var $ORDERS_HISTORY = 'История заказов';
var $UNDEFINED_CATEGORY = 'Не определена';

var $EDIT_USER = 'Редактирование пользователя';
var $PASSWORD = 'Пароль';
var $USER_ENABLED = 'Активен';
var $USER_ENABLE = 'Разблокировать';
var $USER_DISABLE = 'Заблокировать';
var $APPLY_FILTER = 'Применить фильтр';

#Users categories
var $DISCOUNT = 'Скидка';
var $NEW_USERS_CATEGORY = 'Новая группа';
var $EDIT_USERS_CATEGORY = 'Редактирование группы';


#Orders

var $COMMENT = 'Комментарий';
var $STATUS = 'Статус';
var $PCS = 'шт.';
var $IS_IN_STOCK = 'на складе';
var $NOT_IN_STOCK = 'нет на складе';
var $CHANGE_STATUS = 'Изменить статус';
var $NEW_STATUS = 'Новый';
var $ACCEPTED_STATUS = 'Принят';
var $DONE_STATUS = 'Выполнен';
var $EDIT_ORDER = 'Редактирование заказа';

#Statistics

var $LAST_YEAR = 'Последний год';
var $LAST_MONTH = 'Последний месяц';
var $LAST_WEEK = 'Неделя';
var $YESTERDAY = 'Вчера';
var $TODAY = 'Сегодня';
var $CLEAR_STATISTICS = 'Очистить статистику';

var $OLDER_THAN_YEAR = 'Старше года';
var $OLDER_THAN_MONTH = 'Старше месяца';
var $OLDER_THAN_WEEK = 'Старше недели';
var $OLDER_THAN_DAY = 'Старше дня';
var $CLEAR_ALL = 'Всю';
var $STATISTICS_FOR = 'Статистика для ';
var $PRODUCT = 'Товар';
var $GO_TO_MAIN_PAGE = 'На главную';

#Comments
var $USERCOMMENT = 'Отзыв';
var $POINTS = 'Оценка';

#Google sitemap
var $GENERATE = 'Сгенерировать автоматически';

#Pricelist import
var $UPDATE_PRICES = 'Обновить цены';
var $PRICELIST_IMPORT_HELP = 'Скопируйте из файла Excel столбцы с производителем, моделью и ценой, как показано на рисунке, и вставьте в форму ниже';
var $IMPORT_RESULTS = 'Результаты';
var $OLD_PRICE = 'Старая цена';
var $NEW_PRICE = 'Новая цена';
var $NO_PRICES_IMPORTED = 'Ни один товар не найден. Пожалуйста, проверьте прайслист';
var $UPDATED = 'Обновлен';
var $PRODUCT_NOT_FOUND = 'Товар не найден';

#Pricelist export
var $PRICELIST_EXPORT_IN_VARIOUS_FORMATS = 'Экспорт прайса в различные форматы';
var $FORMAT = 'Формат';
var $PRICELIST_URL = 'URL прайса';
var $DOWNLOAD = 'Скачать';

#Products import
var $IMPORT_FROM = 'Импорт из';
var $IMPORT_SHOPSCRIPT_HELP = 'Для импорта товаров из Shop Script зайдите в старом магазине в раздел Каталог -> <nobr>Экспорт товаров в CSV</nobr> и создайте CVS-файл. После импорта скопируйте фотографии товаров в папку foto/storefront/.';
var $PRODUCTS_FILE = 'Файл товаров';
var $START_IMPORT = 'Начать импорт';
var $PRODUCTS_IMPORT_RESULTS = 'Результаты';
var $PRODUCT_ADDED = 'Добавлен';
var $PRODUCT_EXISTS = 'Баян';

#Currencies
var $MAIN_CURRENCY = 'Основная';
var $SIGN = 'Знак';
var $RATE = 'Курс';
var $CODE = 'Код ISO 3';
var $ADD_CURRENCY = 'Добавить валюту';
var $CURRENCY = 'Валюта';

#Settings
var $SITE_NAME = 'Имя сайта';
var $SITE_NAME_HELP = '';
var $COMPANY_NAME = 'Имя компании';
var $COMPANY_NAME_HELP = 'Используется в экспорте прайса в формате Yandex XML';
var $ADMIN_EMAIL = 'Email администратора';
var $ADMIN_EMAIL_HELP = 'На него приходят заказы';
var $MAIN_PAGE = 'Главная страница';
var $MAIN_PAGE_HELP = '';
var $META_TITLE = 'Meta title';
var $META_TITLE_HELP = 'Meta title для главной страницы';
var $META_KEYWORDS = 'Meta keywords';
var $META_KEYWORDS_HELP = 'Meta keywords для главной страницы';
var $META_DESCRIPTION = 'Meta description';
var $META_DESCRIPTION_HELP = 'Meta description для главной страницы';
var $PHONES = 'Телефоны';
var $PHONES_HELP = '';
var $COUNTERS_CODE = 'Коды счетчиков';
var $COUNTERS_CODE_HELP = 'Вставляйте сюда коды счетчиков';
var $FOOTER_TEXT = 'Нижний текст';
var $FOOTER_TEXT_HELP = 'Копирайт';


#Polls
var $POLL = 'Опрос';
var $VOTES_NUMBER = 'Число голосов';
var $NEW_POLL = 'Новый опрос';
var $QUESTION = 'Вопрос';
var $ANSWER = 'Ответ';
var $NEW_ANSWER = 'Добавить вариант ответа';
var $VOTE = 'Ответ';

#Backups
var $BACKUP = 'Резервная копия';

#Payment Methods
var $PAYMENT_METHODS = 'Способы оплаты';

var $LOGOUT = 'Выход';

}

?>