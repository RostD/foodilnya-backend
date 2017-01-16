///<reference path="../../../typings/index.d.ts"/>

import {NgModule} from "@angular/core";
import {BrowserModule} from "@angular/platform-browser";
import {AppComponent} from "./app.component";

//ЗАВОЗИМ КЛАСС ШТОБ ЗАЕБЕНЕТЬ В NgMODULE

@NgModule({
    imports: [BrowserModule],
    declarations: [AppComponent],
    bootstrap: [AppComponent]
})

//ВЫВОЗИМ КЛАСС
export class AppModule {
}