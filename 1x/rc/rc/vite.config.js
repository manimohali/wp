import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react-swc'

// https://vitejs.dev/config/
export default defineConfig(
	{
		build: {
			outDir: 'dist',
			assetsDir: 'assets',
			emptyOutDir: true,
			rollupOptions: {
				output: {
					entryFileNames: 'assets/[name].js',
					chunkFileNames: 'assets/[name].js',
					assetFileNames: 'assets/[name].[ext]', // Custom asset file names ( when we used this it only changes css file name  )
				},
			},
		},

		plugins: [react()],
	}
)
