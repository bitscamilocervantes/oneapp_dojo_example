import '@webcomponents/webcomponentsjs';
import { LitElement, html, css } from 'lit-element';
import WcElement from './../../../shared/utils/wcElement';
import stylesScss from './styles.scss';
import axios from 'axios';

export class DojoExampleStepOne extends WcElement {

    static get properties() {
        return {
            isLoading: { type: Boolean },
            data: { type: Object },
            config: { type: Object },
            showErrorMessage: {type: Boolean}
        };
    }

    static get styles() {
        return [stylesScss];
    }

    constructor() {
        super();
        this.isLoading = false;
        this.data = {};
        this.config = {};
        this.showErrorMessage = true;
    }
    
    renderUser(item, user) {
        console.log(user);
        return html`
            <div class="users">
            ${Object.keys(user).map((item, i) => item == 'avatar' ? 
                html`<div class="user user-${item}">
                    <div class="value"><img src="${user[item].formattedValue}"></div>
                </div>`
            : ``)}
            ${Object.keys(user).map((item, i) => item != 'avatar' && user[item].show ? 
                html`<div class="user user-${item}">
                    <span class="title">${user[item].label}</span>
                    <div class="value">${user[item].formattedValue}</div>
                </div>`
            : ``)}   
            </div>                              
            <br/>
        `;
    }

    render() {
        return html`
            <div class="card-info">
                <div class="${this.isLoading ? 'backdrop-loading': '' }"></div>
                <div class="wrapper-block clearfix">
                    <h3 class="title">${ this.config?.title?.value }</h3>
                    <div class="content clearfix">
                        <div class="dojo-example-step-one">
                                ${Object.keys(this.data.users).map((item, i) => true ? 
                                    this.renderUser(item, this.data.users[item]) 
                                : ``)}                             
                                ${this.config?.actions?.showStatsButton?.show ? html`
                                    <div class="actions">
                                        <div class="buttons-right m-24 align-right">
                                            <button class="at-button-primary" @click="${this.step2}">${ this.config?.actions?.showStatsButton?.label }</button>
                                        </div>
                                    </div>
                                `: ``}
                        </div>
                    </div>
                </div>
            </div>`;
    }

    step2(){
        this.sendSegmentTrack('Users List View Stats Button Clicked');
        this.emmitEvent('wcSteps', {goto: 2, data: {
            'data' : {
                'page' : this.wcData.page,
                'users' : this.data.users.length
            },
            'config' : {
                'title' : this.config.title,
                'actions' : Object.keys(this.config.actions).map((item, i) => item != 'changeFormatButton' && item != 'updateFormatButton' ?
                    this.config.actions[item] : ''
                )
            }
        }});
    }

    closeErrorMessage(){
        this.showErrorMessage = false;
    }

    init() {
        console.log('WCDATA-hijo', this.wcData);
        this.getDataApi();
    }

    async getDataApi() {

        

        const token = localStorage.getItem('IdToken');
        // const options = {
        //     headers: {Authorization: `Bearer ${token}`}
        // };
        const options = {};
        const apiUrl = `${this.wcData.urlBase}/api/v2.0/${this.wcData.businessUnit}/users/${this.wcData.page}/list?_format=json`;

        this.statusLoading(true);
        this.isLoading = true;

        await axios.get(apiUrl, options)
            .then((response) => {

                this.data = response.data.data;
                this.config = response.data.config;
                console.log(this.config);


                this.statusLoading(false);
                this.isLoading = false;
                this.sendSegmentTrack('Dojo Example Users List Loaded');

            }).catch((error) => {

                this.sendSegmentTrack('Dojo Example Users List Failed');
                let errorData = null;
                if (error.response) {
                    errorData = error.response.data;
                    if (error.response.status == 401) {
                        this.refreshToken();
                    }
                    else {
                        if(error.response.status == 503){
                            this.data = error.response.data.data;
                            console.log('error.response', error.response.data.data);
                            this.config = error.response.data.config;
                            console.log('this.data',this.data)
                            this.statusLoading(false);
                            this.isLoading = false;
                        } else {
                            if (errorData != null && errorData.message != null) {
                                this.message(errorData.message, 'error');
                            }
                        }
                        this.statusLoading(false);
                    }
                }
                else {
                    this.statusLoading(false);
                }
            });
    }

    sendSegmentTrack(name){
        this.sendInfoToSegment(name, {
            journey: 'dojo example user list info',
            linked_msisdns: this.wcData.page,
        });
    }

    createRenderRoot() { return this; }

}
