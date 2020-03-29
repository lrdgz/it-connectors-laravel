import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter, Link, Route, Switch} from 'react-router-dom';
import Main from './Router';


class Index extends Component {
    render() {
        return (
            <BrowserRouter>
                <Route component={Main} />
            </BrowserRouter>
        );
    }
}
ReactDOM.render(<Index/>, document.getElementById('app'));

// https://kaloraat.com/articles/laravel-react-crud-tutorial
// https://kaloraat.com/articles/laravel-react-crud-tutorial-part-2
// https://medium.com/@000kelvin/setting-up-laravel-and-react-js-the-right-way-using-user-authentication-1cfadf3194e
// https://www.itsolutionstuff.com/post/laravel-5-simple-crud-application-using-reactjs-part-3example.html
// https://github.com/danielcrt/laravel-reactjs-authentication-boilerplate


// https://www.youtube.com/watch?v=Nt4lURq-j4w&list=PLkZU2rKh1mT8CkcDUU-YSeJSKtsKWL_TW


