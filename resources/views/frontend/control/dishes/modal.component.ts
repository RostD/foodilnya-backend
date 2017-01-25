/**
 * Created by Ростислав on 23.01.2017.
 */
import {Component, OnInit, ElementRef, ViewChild} from "@angular/core";
import {Dish} from "./dish";

declare var jQuery:any;

@Component({
    selector: 'modal',
    templateUrl: '/app/control/dishes/view/modal.component.html'
})
export class ModalComponent implements OnInit {

    @ViewChild('modalDOM') modalDOM:ElementRef;

    /**
     * Оригинальный объект Dish
     */
    private dish_orig:Dish;

    /**
     * Копия оригинального объекта Dish, над которым производятся
     * неподтвержденные изменения
     */
    private edited = new Dish();

    private attr_manipulated = null
    private buffer = null;

    private idPrefixAttrShowValue = "showValue_";
    private idPrefixAttrInputValue = "inputValue_";
    private idAttrAdd = "addTr";

    ngOnInit():void {
    }

    /**
     *
     * @param dish : Dish оригинальный объект dish,
     * над которым будут производиться изменения
     */
    setDish(dish:Dish) {
        this.dish_orig = dish;

        this.edited = jQuery.extend(true, {}, dish);
        // or clone obj with JSON:
        // this.edited = JSON.parse(JSON.stringify(dish));
    }

    /**
     * @param dom_id id DOOM элемента атрибута
     */
    editAttrVal(dom_id, attribute) {
        this.attr_manipulated = attribute;
        this.buffer = attribute.value;

        this.JQ().find('#' + this.idPrefixAttrInputValue + dom_id).show().focus().select();
        this.JQ().find('#' + this.idPrefixAttrShowValue + dom_id).hide();
    }

    submitAttrVal(dom_id) {
        this.attr_manipulated = null;
        this.buffer = null;

        this.JQ().find('#' + this.idPrefixAttrInputValue + dom_id).hide();
        this.JQ().find('#' + this.idPrefixAttrShowValue + dom_id).show();
    }

    returnAttrVal(dom_id) {
        this.attr_manipulated.value = this.buffer;
        this.submitAttrVal(dom_id);
    }

    JQ(string:string = null) {
        if (string == null)
            return jQuery(this.modalDOM.nativeElement);
        else
            return jQuery(string);
    }

    /**
     * Составление списка атрибутов на удаление
     * @param attribute
     */
    deleteList(attribute) {
        if (attribute.checked)
            attribute.checked = false;
        else
            attribute.checked = true;
    }

    /**
     * Удаление выбранных атрибутов
     */
    deleteAttrs() {

        for (var i = 0; i < this.edited.attributes.length; i++) {
            if (this.edited.attributes[i].checked) {
                this.edited.attributes.splice(i, 1);
                i--;
            }
        }
    }

    /**
     * Сохранение внесенных изменений
     */
    save() {
        for (var index in this.edited) {
            this.dish_orig[index] = this.edited[index];
        }
        this.setDish(this.dish_orig);
    }

    private addAttr() {
        //TODO: Динамическое создание элементов
        console.log("Метода вызвана");
        var tr = this.JQ("<tr id='" + this.idAttrAdd + "'></tr>");
        tr.html(`<td colspan="2">
                    <select (click)="showInputValue()">
                        <option>One</option>
                        <option>Two</option>
                        <option>Three</option>
                    </select>
                </td>`);
        this.JQ().find("table").append(tr);
    }

    private showInputValue() {
        console.log("Вызывнао нах");
    }
}