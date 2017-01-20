/// <reference path="../../cfg.ts"/>
import {Component} from "@angular/core";


//Декоратор
@Component({
    selector: 'my-app',
    templateUrl: _url('app/control/dishes/view/app.component.html'),
})
export class AppComponent {
    text:string;

    constructor() {
        this.text = "Angular 2 подгружен";
    }
}