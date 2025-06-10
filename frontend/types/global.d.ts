declare global {
  interface Window {
    $toast?: {
      success: (title: string, message: string) => void
      error: (title: string, message: string) => void
    }
  }
}

export {}