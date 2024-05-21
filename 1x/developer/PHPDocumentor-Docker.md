To use PHPDocumentor with Docker to generate documentation tags, including adding `@package` tags, follow these steps:

### Step-by-Step Guide

1. **Pull the PHPDocumentor Docker Image**:
   
   First, you need to pull the PHPDocumentor Docker image from Docker Hub. Run the following command in your terminal:

   ```bash
   docker pull phpdoc/phpdoc
   ```

2. **Run PHPDocumentor in a Docker Container**:
   
   Next, you need to run PHPDocumentor using Docker. The command below runs PHPDocumentor, mounts the current directory (`$(pwd)`) to `/data` inside the Docker container, and generates documentation for your project.

   ```bash
   docker run --rm -v $(pwd):/data phpdoc/phpdoc
   ```

   This command will generate documentation based on the PHP files in your current directory. The generated documentation will be placed in the `output` directory by default.

### Detailed Explanation

1. **Pulling the Docker Image**:

   ```bash
   docker pull phpdoc/phpdoc
   ```

   - `docker pull phpdoc/phpdoc`: This command pulls the latest PHPDocumentor image from Docker Hub. Docker images are like templates used to create Docker containers.

2. **Running the Docker Container**:

   ```bash
   docker run --rm -v $(pwd):/data phpdoc/phpdoc
   ```

   - `docker run`: This command runs a Docker container based on the specified image.
   - `--rm`: This flag tells Docker to automatically remove the container when it exits.
   - `-v $(pwd):/data`: This option mounts the current directory (`$(pwd)`) to the `/data` directory inside the container. This allows the container to access your project files.
   - `phpdoc/phpdoc`: This is the Docker image we pulled earlier.
   
   When the container runs, PHPDocumentor will read the PHP files in the `/data` directory (which maps to your current directory) and generate the documentation.

### Example Usage

1. **Navigate to Your Project Directory**:

   Open your terminal and navigate to the root directory of your PHP project. For example:

   ```bash
   cd /path/to/your/project
   ```

2. **Pull the PHPDocumentor Docker Image**:

   Run the following command to pull the PHPDocumentor image:

   ```bash
   docker pull phpdoc/phpdoc
   ```

3. **Generate Documentation**:

   Run the PHPDocumentor Docker container to generate the documentation:

   ```bash
   docker run --rm -v $(pwd):/data phpdoc/phpdoc
   ```

### Customizing PHPDocumentor Configuration

You can customize the behavior of PHPDocumentor by creating a `phpdoc.xml` configuration file in your project directory. Here’s a basic example of a `phpdoc.xml` configuration file that includes the `@package` tag:

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<phpdoc>
    <title>My Project Documentation</title>
    <directories>
        <directory>./src</directory>
    </directories>
    <files>
        <file>./example.php</file>
    </files>
    <parser>
        <target>./output</target>
        <extensions>php</extensions>
        <ignore hidden="true" symlinks="false">
            <directory>vendor</directory>
            <directory>tests</directory>
        </ignore>
    </parser>
    <transformer>
        <target>./output</target>
    </transformer>
    <transformations>
        <transformation writer="FileIo" source="templates/responsive/index.xsl" artifact="index.html"/>
    </transformations>
</phpdoc>
```

This configuration file specifies:

- The title of the documentation.
- Directories and files to include.
- The target directory for the generated documentation.
- File extensions to parse.
- Directories to ignore.

To use this configuration file, place it in your project root and run PHPDocumentor with Docker:

```bash
docker run --rm -v $(pwd):/data phpdoc/phpdoc -d . -t ./output
```

This will use the `phpdoc.xml` configuration file to generate the documentation.

By following these steps, you can generate documentation for your PHP project using PHPDocumentor with Docker, including adding necessary tags like `@package`.