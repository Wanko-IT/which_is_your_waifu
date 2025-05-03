<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
          アカウント登録
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          あなたの嫁を選ぶために、新しいアカウントを作成してください
        </p>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="register">
        <div class="-space-y-px rounded-md shadow-sm">
          <div>
            <label for="name" class="sr-only">名前</label>
            <input
              id="name"
              name="name"
              type="text"
              required
              v-model="name"
              class="relative block w-full rounded-t-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-pink-600 sm:text-sm sm:leading-6"
              placeholder="名前"
            />
          </div>
          <div>
            <label for="email-address" class="sr-only">メールアドレス</label>
            <input
              id="email-address"
              name="email"
              type="email"
              autocomplete="email"
              required
              v-model="email"
              class="relative block w-full border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-pink-600 sm:text-sm sm:leading-6"
              placeholder="メールアドレス"
            />
          </div>
          <div>
            <label for="password" class="sr-only">パスワード</label>
            <input
              id="password"
              name="password"
              type="password"
              autocomplete="new-password"
              required
              v-model="password"
              class="relative block w-full border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-pink-600 sm:text-sm sm:leading-6"
              placeholder="パスワード"
            />
          </div>
          <div>
            <label for="password-confirmation" class="sr-only">パスワード（確認）</label>
            <input
              id="password-confirmation"
              name="password_confirmation"
              type="password"
              autocomplete="new-password"
              required
              v-model="passwordConfirmation"
              class="relative block w-full rounded-b-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-pink-600 sm:text-sm sm:leading-6"
              placeholder="パスワード（確認）"
            />
          </div>
        </div>

        <div>
          <button
            type="submit"
            class="group relative flex w-full justify-center rounded-md bg-pink-600 py-2 px-3 text-sm font-semibold text-white hover:bg-pink-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-pink-600"
          >
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
              <svg class="h-5 w-5 text-pink-500 group-hover:text-pink-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
              </svg>
            </span>
            登録する
          </button>
        </div>
      </form>
      
      <div class="text-center mt-4">
        <p class="text-sm text-gray-600">
          すでにアカウントをお持ちですか？
          <nuxt-link to="/login" class="font-medium text-pink-600 hover:text-pink-500">ログイン</nuxt-link>
        </p>
      </div>
      
      <div v-if="error" class="mt-4 p-3 bg-red-100 text-red-700 rounded-md text-sm">
        {{ error }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const error = ref('')

const register = async () => {
  try {
    error.value = ''
    
    // パスワード確認チェック
    if (password.value !== passwordConfirmation.value) {
      error.value = 'パスワードが一致しません'
      return
    }
    
    // 登録リクエスト
    const response = await fetch(useRuntimeConfig().public.apiBase + '/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      credentials: 'include',
      body: JSON.stringify({
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: passwordConfirmation.value
      })
    })
    
    const data = await response.json()
    
    if (!response.ok) {
      throw new Error(data.message || '登録に失敗しました')
    }
    
    // 登録成功
    router.push('/')
  } catch (err) {
    error.value = err.message || '登録中にエラーが発生しました'
    console.error('Registration error:', err)
  }
}
</script>
