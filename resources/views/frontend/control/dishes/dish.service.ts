/**
 * Created by Ростислав on 24.01.2017.
 */
import {Inject, Injectable} from "@angular/core";
import {Http} from "@angular/http";
import "rxjs/add/operator/map";


@Injectable()
export class DishService {

    constructor(@Inject(Http) private http:Http) {
    }

    getDishes() {
        return this.http.get('/api/dish').map(
            (res) => res.json()
        );
    }

}