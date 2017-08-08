<?php

namespace InetStudio\Articles\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class SaveArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Сообщения об ошибках.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'meta.title.max' => 'Поле «Title» не должно превышать 255 символов',
            'meta.description.max' => 'Поле «Description» не должно превышать 255 символов',
            'meta.keywords.max' => 'Поле «Keywords» не должно превышать 255 символов',

            'meta.og:title.max' => 'Поле «og:itle» не должно превышать 255 символов',
            'meta.og:description.max' => 'Поле «og:description» не должно превышать 255 символов',

            'title.required' => 'Поле «Заголовок» обязательно для заполнения',
            'title.max' => 'Поле «Заголовок» не должно превышать 255 символов',

            'slug.required' => 'Поле «URL» обязательно для заполнения',
            'slug.alpha_dash' => 'Поле «URL» может содержать только латинские символы, цифры, дефисы и подчеркивания',
            'slug.max' => 'Поле «URL» не должно превышать 255 символов',
            'slug.unique' => 'Такое значение поля «URL» уже существует',

            'preview.file.required' => 'Поле «Превью» обязательно для заполнения',
            'preview.crop.3_2.required' => 'Необходимо выбрать область отображения 3x2',
            'preview.crop.3_4.required' => 'Необходимо выбрать область отображения 3x4',
            'preview.description.max' => 'Поле «Описание» не должно превышать 255 символов',
            'preview.copyright.max' => 'Поле «Copyright» не должно превышать 255 символов',
            'preview.alt.required' => 'Поле «Alt» обязательно для заполнения',
            'preview.alt.max' => 'Поле «Alt» не должно превышать 255 символов',

            'tags.array' => 'Поле «Теги» должно содержать значение в виде массива',

            'publish_date' => 'Поле «Время публикации» должно быть в формате дд.мм.гггг чч:мм',
        ];
    }

    /**
     * Правила проверки запроса.
     *
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'meta.title' => 'max:255',
            'meta.description' => 'max:255',
            'meta.keywords' => 'max:255',
            'meta.og:title' => 'max:255',
            'meta.og:description' => 'max:255',
            'title' => 'required|max:255',
            'slug' => 'required|alpha_dash|max:255|unique:articles,slug,' .$request->get('article_id'),
            //'preview.file' => 'required',
            'preview.crop.3_2' => 'required',
            'preview.crop.3_4' => 'required',
            'preview.description' => 'max:255',
            'preview.copyright' => 'max:255',
            'preview.alt' => 'required|max:255',
            'tags' => 'array',
            'publish_date' => 'date_format:d.m.Y H:i'
        ];
    }
}