#!/usr/bin/env python3
"""Package only the distributable WordPress theme, never local configuration."""
from pathlib import Path
from zipfile import ZipFile, ZIP_DEFLATED
import hashlib

root = Path(__file__).resolve().parents[1]
source = root / 'wordpress/theme/bellaaquabella'
target = root / 'public/downloads'
target.mkdir(parents=True, exist_ok=True)
archive = target / 'bellaaquabella-theme.zip'
required = {'bellaaquabella/style.css', 'bellaaquabella/index.php', 'bellaaquabella/functions.php'}
with ZipFile(archive, 'w', compression=ZIP_DEFLATED, compresslevel=9) as package:
    for file in sorted(source.rglob('*')):
        if file.is_symlink():
            raise ValueError('Theme symlinks must be reviewed before packaging.')
        if not file.is_file():
            continue
        relative = file.relative_to(source)
        if any(part.startswith('.') for part in relative.parts) or file.name == 'wp-config.php':
            raise ValueError('Unexpected configuration file in theme.')
        package.write(file, 'bellaaquabella/' + relative.as_posix())
with ZipFile(archive) as package:
    assert required <= set(package.namelist()), 'Incomplete WordPress theme'
    assert package.testzip() is None, 'Corrupt archive'
digest = hashlib.sha256(archive.read_bytes()).hexdigest()
(target / 'bellaaquabella-theme.sha256').write_text(f'{digest}  {archive.name}\n')
print(f'Prepared {archive.name} ({archive.stat().st_size:,} bytes), theme files only.')
