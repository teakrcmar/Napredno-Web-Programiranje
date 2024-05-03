var createError = require('http-errors');
var express = require('express');
var path = require('path');
var cookieParser = require('cookie-parser');
var logger = require('morgan');
var bodyParser = require('body-parser');
var multer = require('multer');

var register = require('./routes/register');
var messages = require('./lib/messages');
var login = require('./routes/login');
var user = require('./lib/middleware/user');
var validate = require('./lib/middleware/validate');
var api = require('./routes/api');

var index = require('./routes/index');
var users = require('./routes/users');

var session = require('express-session');
var methodOverride = require('method-override');

var Project = require("./lib/project");
var projects = require("./routes/projects");

var app = express();

app.use(methodOverride('X-HTTP-Method-Override'));

app.use(session({
    secret: 'keyboard cat',
    resave: false,
    saveUninitialized: true,
    cookie: { secure: false }
}))

app.use(messages);

app.use(user);

app.use('/api', api.auth);


app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'jade');

app.use(logger('dev'));
app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, 'public')));

app.get('/register', register.form);
app.post('/register', register.submit);

app.get('/login', login.form);
app.post('/login', login.submit);
app.get('/logout', login.logout);

app.get('/post', projects.form);

app.post(
    '/post',
    validate.required('name'),
    validate.lengthAbove('name', 4),
    validate.required('desc'),
    validate.required('cost'),
    validate.required('tasks'),
    validate.required('date_start'),
    validate.required('date_finish'),
    projects.submit
);

app.get('/', projects.index);
app.get('/projects', projects.list);
app.post("/delete", projects.delete);
app.post("/addMember", projects.addMember);
app.use('/users', users);
app.get("/membership", projects.membership);
app.get("/editProject", projects.editProject);
app.post("/archive", projects.archive);
app.post("/changeProject", projects.changeProject);

app.get('/api/user/:id', api.user);


app.use(function (req, res, next) {
    next(createError(404));
});

app.use(function (err, req, res, next) {
    res.locals.message = err.message;
    res.locals.error = req.app.get('env') === 'development' ? err : {};

    res.status(err.status || 500);
    res.render('error');
});

module.exports = app;
