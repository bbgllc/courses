@extends('layouts.master')

@section('title', app_name() . ' | ' . __('t.create-course'))

@section('content')
    
    
    @include('includes._title_header', ['title' => __('t.create-course')])

    
    <section class="content-area bg-gray pt-5 pb-5">
        <div class="container">
            
            <div class="row mb-4">
                <div class="col-md-8 offset-md-2">
                    
                    
                    
                    <author-create-course inline-template>
                        <div class="card border-info">
                            <div class="card-header text-center">
                                <h5>{{__('t.it-all-starts-here')}}</h5>
                            </div>
                            <div class="card-body">
                                
                                
                                <form @submit.prevent="saveCourse" @keydown="form.onKeydown($event)">
                                    <div class="form-row">
                                        <div class="form-group col">
                                            <select class="form-control form-control-md ui dropdown" v-model="form.parent_category" @change="fetchSubcategories">
                                                <option value>{{__('t.select-category')}}</option>
                                                <option v-for="category in categories" :value="category.id">@{{category.name}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group col">
                                            <select class="form-control form-control-md" :class="{ 'is-invalid': form.errors.has('category') }" v-model="form.category">
                                                <option value>{{__('t.select-subcategory')}}</option>
                                                <option v-for="subcategory in subcategories" :value="subcategory.id">@{{subcategory.name}}</option>
                                            </select>
                                            <has-error :form="form" field="category"></has-error>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group has-float-label">
                                      <input v-model="form.title" @keyup="generateSlug" autocomplete="off" type="text" name="title" id="title" placeholder="{{__('t.enter-title')}}"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                                        {{ html()->label(__('t.title'))->for('title') }}
                                        <has-error :form="form" field="title"></has-error>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="basic-addon1">{{config('app.url')}}courses/</span>
                                            <input v-model="form.slug" type="text" id="slug" name="slug"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('slug') }">
                                        </div>
                                        <has-error :form="form" field="slug"></has-error>
                                        
                                    </div>
                                    
                                    <div class="form-group has-float-label">
                                      <input v-model="form.subtitle" @keyup="generateSlug" autocomplete="off" type="text" name="subtitle" id="subtitle" placeholder="{{__('t.learn-how-to')}}..."
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('subtitle') }">
                                        {{ html()->label(__('t.subtitle'))->for('subtitle') }}
                                        <has-error :form="form" field="subtitle"></has-error>
                                    </div>
                                
                                    <button :disabled="form.busy" type="submit" class="btn btn-block btn-info">{{__('t.create-course')}}</button>
                                </form>

                            </div>
                        </div>
                    </author-create-course>
                </div>
                
            </div>

            
        </div>
    </section>

@endsection

