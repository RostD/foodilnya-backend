import {Component} from "@angular/core";

//ЕБУЧИЙ КОМПОНЕНТ
@Component({
    selector: 'my-app',
    template: '<ul><li *ngFor="let name of names ">{{name}}</li></ul>'
})

// ВЫВОЗИМ КЛАС НАХУЙ ИЗ ДОКУМЕНТА
export class AppComponent {
    names:string[];

    constructor() {
        this.names = ['fitsr', 'second', 'third', 'fffffff'];
    }
}