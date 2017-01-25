///<reference path="../../../../../typings/index.d.ts"/>

// Модули
import {HttpModule} from "@angular/http";
import {NgModule} from "@angular/core";
import {BrowserModule} from "@angular/platform-browser";
import {FormsModule} from "@angular/forms";
import {AppComponent} from "./app.component";
import {TableComponent} from "./table.component";
import {ModalComponent} from "./modal.component";

//Компоненты


@NgModule({
    imports: [BrowserModule, HttpModule, FormsModule],
    declarations: [AppComponent, TableComponent, ModalComponent],
    bootstrap: [AppComponent]
})

//ВЫВОЗИМ КЛАСС
export class AppModule {
}